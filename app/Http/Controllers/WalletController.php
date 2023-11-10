<?php

namespace App\Http\Controllers;

use App\enums\TransactionTypes;
use App\Exceptions\ChargeCodeLimitCountReached;
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
    /**
     * @throws ChargeCodeLimitCountReached
     * @throws ChargeCodeHasBeenUsedException
     */
    public function applyChargeCode(ApplyChargeCode $applyChargeCodeReq){
        $user=User::query()->whereUsername($applyChargeCodeReq->get("username"))->first();
        DB::beginTransaction();
        try {
            //retrieve charge code usage_limit
            $chargeCodeUsageLimit=ChargeCode::query()
                ->select("usage_limit")
                ->where("code",$applyChargeCodeReq->get("charge_code"))
                ->first()->usage_limit;
            //lock charge code for update
            $chargeCode=ChargeCode::query()
                ->where("code",$applyChargeCodeReq->get("charge_code"))
                ->where("usage_count","<",$chargeCodeUsageLimit)
                ->lockForUpdate()
                ->first();
            // check the charge_code reached its usage_limit that admin defined
            if($chargeCode==null){
                throw new ChargeCodeLimitCountReached("charge_code_limit_count_reached");
            }
            // check does user have used this charge_code before
            if($user->chargeCodes()->wherePivot("charge_code_id",$chargeCode->id)->exists()){
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
            // update user charge_codes and set the charge_code for user
            $user->chargeCodes()->save($chargeCode);
            // update usage_count (instead of querying all the user_charge_codes table this filed will be used to reach the usage_count fast )
            $chargeCode->update(["usage_count"=>++$chargeCode->usage_count]);
            DB::commit();
            return response()->json(["message"=>"charge_code_successfully_applied"]);
        }
        catch (\Exception $exception){
            DB::rollBack();
            return match (true) {
                $exception instanceof ChargeCodeLimitCountReached => response()->json(["message" => "charge_code_limit_count_reached"], Response::HTTP_BAD_REQUEST),
                $exception instanceof ChargeCodeHasBeenUsedException => response()->json(["message" => "charge_code_has_been_used"], Response::HTTP_BAD_REQUEST),
                default => throw $exception,
            };
        }
    }

}
