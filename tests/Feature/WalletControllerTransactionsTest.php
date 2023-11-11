<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\TransactionInformation;
use App\Models\TransactionInformationKeys;
use App\Models\TransactionType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class WalletControllerTransactionsTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_not_get_transactions_when_user_does_not_exists(){
        $this
            ->getJson("/api/v1/wallet/transactions/"."+98901111111")
            ->assertStatus(404);
    }
    public function test_user_get_transactions_list_successfully(){
        //arrange
        $this->seed();
        $user=User::factory()->create();
        $transactionType=TransactionType::query()->whereName("charge_code")->first();
        Transaction::factory()->count(50)->create(["user_id"=>$user->id,"type_id"=>$transactionType->id]);
        $this
            ->getJson("/api/v1/wallet/transactions/".$user->username)
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json){
                $json->has("data",50)
                    ->has("data.0",function (AssertableJson $json){
                        $json
                            ->has("id")
                            ->has("amount")
                            ->has("type")
                            ->has("created_at");
                    });
            });
    }
    public function test_user_get_specific_transactions_successfully(){
        //arrange
        $this->seed();
        $user=User::factory()->create();
        $transactionType=TransactionType::query()->whereName("charge_code")->first();
        $transaction=Transaction::factory()->create(["user_id"=>$user->id,"type_id"=>$transactionType->id]);
        TransactionInformation::query()->create(
            [
                "transaction_id"=>$transaction->id,
                "ti_key_id"=>TransactionInformationKeys::query()->whereName("charge_code")->first()->id,
                "value"=>"some_information"
            ]
        );

        $this
            ->getJson("/api/v1/wallet/transactions/".$user->username."/".$transaction->id)
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json){
                $json->has("data")
                    ->has("data",function (AssertableJson $json){
                        $json
                            ->has("id")
                            ->has("amount")
                            ->has("type")
                            ->has("created_at")
                            ->has("information",1)
                            ->has("information.0",function (AssertableJson $json){
                                $json
                                    ->has("key")
                                    ->where("key","charge_code")
                                    ->has("value")
                                    ->where("value","some_information");
                            });
                    });
            });
    }
}
