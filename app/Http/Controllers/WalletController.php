<?php

namespace App\Http\Controllers;

use App\enums\TransactionTypes;
use App\Exceptions\CouponCodeHasBeenUsedException;
use App\Http\Requests\ApplyCouponCodeRequest;

use App\Models\CouponCode;
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
    public function applyCoupon(ApplyCouponCodeRequest $couponCodeRequest){
        $couponCode=$couponCodeRequest->get("coupon_code");
        $user=User::query()->whereUsername($couponCodeRequest->get("username"))->first();

        DB::beginTransaction();
        try {
            //retrieve coupon
            $couponRecord=CouponCode::query()
                ->where("code",$couponCode)
                ->whereNull("user_id")
                ->lockForUpdate()
                ->first();
            if($couponRecord==null){
                throw new CouponCodeHasBeenUsedException("coupon_has_been_used_or_does_not_exists");
            }
            $transaction=Transaction::query()->create(
                [
                    "amount"=>$couponRecord->amount,
                    "type_id"=>TransactionType::query()->whereName("coupon")->first()->id,
                    "user_id"=>$user->id
                ]
            );
            TransactionInformation::query()->create(
                [
                    "transaction_id"=>$transaction->id,
                    "ti_key_id"=>TransactionInformationKeys::query()->whereName("coupon_code")->first()->id,
                    "value"=>$couponRecord->code
                ]
            );
            $couponRecord->update(["user_id"=>$user->id]);
            DB::commit();
            return response()->json(["message"=>"coupon_successfully_applied"]);
        }catch (CouponCodeHasBeenUsedException $exception){
            DB::rollBack();
            return response()->json(["message"=>"coupon_has_been_used_or_does_not_exists", Response::HTTP_BAD_REQUEST]);
        } catch (\Exception $exception){
            Log::error($exception->getMessage(),$exception->getTrace());
            DB::rollBack();
            return response()->json(
                ["message"=>"something_went_wrong_during_applying_coupon_code"]
            ,Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
