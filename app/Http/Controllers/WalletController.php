<?php

namespace App\Http\Controllers;

use App\enums\TransactionTypes;
use App\Exceptions\CouponCodeHasBeenUsedException;
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
    public function applyChargeCode(ApplyChargeCode $couponCodeRequest){
        $couponCode=$couponCodeRequest->get("charge_code");
        $user=User::query()->whereUsername($couponCodeRequest->get("username"))->first();

        DB::beginTransaction();
        try {
            //retrieve coupon
            $chargeCode=ChargeCode::query()
                ->where("code",$couponCode)
                ->where("usage_count","<","limit_count")
                ->lockForUpdate()
                ->first();

            if($chargeCode==null){
                throw new CouponCodeHasBeenUsedException("coupon_has_been_used_or_does_not_exists");
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
            $chargeCode->update(["user_id"=>$user->id]);
            DB::commit();
            return response()->json(["message"=>"charge_code_successfully_applied"]);
        }catch (CouponCodeHasBeenUsedException $exception){
            DB::rollBack();
            return response()->json(["message"=>"charge_code_has_been_used_or_does_not_exists"],Response::HTTP_BAD_REQUEST);
        } catch (\Exception $exception){
            Log::error($exception->getMessage(),$exception->getTrace());
            DB::rollBack();
            return response()->json(
                ["message"=>"something_went_wrong_during_applying_charge_code_code"]
            ,Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
