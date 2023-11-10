<?php

namespace App\Http\Controllers;

use App\enums\TransactionTypes;
use App\Exceptions\ChargeCodeDoesNotExistsOrLimitCountReached;
use App\Exceptions\ChargeCodeHasBeenUsedException;
use App\Http\Requests\ApplyChargeCode;

use App\Models\ChargeCode;
use App\Models\Transaction;
use App\Models\TransactionInformation;
use App\Models\TransactionInformationKeys;
use App\Models\TransactionType;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response ;

class WalletController extends Controller
{
    public function applyChargeCode(ApplyChargeCode $applyChargeCodeReq){
        $chargeCode=$applyChargeCodeReq->get("charge_code");
        $user=User::query()->whereUsername($applyChargeCodeReq->get("username"))->first();

        DB::beginTransaction();
        try {
            //retrieve coupon
            $chargeCode=ChargeCode::query()
                ->where("code",$chargeCode)
                ->where("usage_count","<","usage_limit")
                ->lockForUpdate()
                ->first();
            if($chargeCode==null){
                throw new ChargeCodeDoesNotExistsOrLimitCountReached("charge_code_does_not_exists_or_limit_count_reached");
            }
            $hasUserUsedChargeCode=$user->chargeCodes()->wherePivot("charge_code_id",$chargeCode->id)->exists();
            if($hasUserUsedChargeCode){
                throw new ChargeCodeHasBeenUsedException("coupon_has_been_used");
            }
            $transaction=Transaction::query()->create(
                [
                    "amount"=>$chargeCode->amount,
                    "type_id"=>TransactionType::query()->whereName("charge_code")->first()->id,
                    "user_id"=>$user->id
                ]
            );
            TransactionInformation::query()->create(
                [
                    "transaction_id"=>$transaction->id,
                    "ti_key_id"=>TransactionInformationKeys::query()->whereName("charge_code")->first()->id,
                    "value"=>$chargeCode->code
                ]
            );
            $user->chargeCodes()->save($chargeCode);
            $chargeCode->update(["usage_count"=>++$chargeCode->usage_count]);
            DB::commit();
            return response()->json(["message"=>"charge_code_successfully_applied"]);
        }
        catch (ChargeCodeDoesNotExistsOrLimitCountReached $exception){
            DB::rollBack();
            return response()->json(["message"=>"charge_code_does_not_exists_or_limit_count_reached"],Response::HTTP_BAD_REQUEST);
        }
        catch (ChargeCodeHasBeenUsedException $exception){
            DB::rollBack();
            return response()->json(["message"=>"charge_code_has_been_used"],Response::HTTP_BAD_REQUEST);
        }
        catch (\Exception $exception){
            Log::error($exception->getMessage(),$exception->getTrace());
            DB::rollBack();
            return response()->json(
                ["message"=>"something_went_wrong_during_applying_charge_code_code"]
            ,Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
