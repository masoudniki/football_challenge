<?php

namespace Services\Wallet\tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Services\Wallet\Models\Transaction;
use Services\Wallet\Models\TransactionType;
use Tests\TestCase;

class WalletControllerGetCreditTest extends TestCase
{
    use RefreshDatabase;
    public function test_can_not_get_user_credit_while_user_does_not_exists(){
        $this
            ->getJson("/api/v1/wallet/credit",["user"=>'+98901111111'])
            ->assertStatus(404);
    }
    public function test_user_get_its_credit_successfully(){
        //arrange
        $this->seed();
        $user=User::factory()->create();
        $transactionType=TransactionType::query()->whereName("charge_code")->first();
        $transactions=Transaction::factory()->count(50)->create(["user_id"=>$user->id,"type_id"=>$transactionType->id]);
        //act
        $this
            ->getJson("/api/v1/wallet/credit/".$user->username)
            //assertions
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use($transactions){
                $json
                    ->has("credit")
                    ->where("credit",$transactions->sum(function (Transaction $transaction){
                        return $transaction->amount;
                    }));
            });

    }

}
