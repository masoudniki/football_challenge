<?php

namespace Tests\Feature;

use App\Models\ChargeCode;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class WalletControllerApplyChargeCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_not_apply_charge_code_when_username_does_not_exists(){
        $this
            ->postJson("/api/v1/wallet/applyChargeCode",[
                "username"=>"+989011111111",
                "charge_code"=>"tktkt",
            ])
            ->assertStatus(422)
            ->assertJson(function (AssertableJson $json){
                $json
                    ->has("message")
                    ->has("errors")
                    ->has("errors.username",1)
                    ->where("errors.username.0","The selected username is invalid.");
            });
    }
    public function test_user_can_not_apply_charge_code_when_username_does_not_match_with_regex(){
        $this
            ->postJson("/api/v1/wallet/applyChargeCode",[
                "username"=>"0901",
                "charge_code"=>"tktkt",
            ])
            ->assertStatus(422)
            ->assertJson(function (AssertableJson $json){
                $json
                    ->has("message")
                    ->has("errors")
                    ->has("errors.username",1)
                    ->where("errors.username.0","The username field format is invalid.");
            });
    }
    public function test_user_can_not_apply_charge_code_when_charge_code_does_not_exists(){
        //arrange
        $user=User::factory()->create();
        //act
        $this
            ->postJson("/api/v1/wallet/applyChargeCode",[
                "username"=>$user->username,
                "charge_code"=>"tktkt",
            ])

            //assertions
            ->assertStatus(422)
            ->assertJson(function (AssertableJson $json){
                $json
                    ->has("message")
                    ->has("errors",1)
                    ->where("errors.charge_code.0","The selected charge code is invalid.");
            });
    }
    public function test_user_can_apply_charge_code_successfully(){
        //arrange
        $this->seed();
        $user=User::factory()->create();
        $chargeCode=ChargeCode::factory()->create();
        //act
        $this->postJson("/api/v1/wallet/applyChargeCode",
            [
                "username"=>$user->username,
                "charge_code"=>$chargeCode->code
            ]
        )
            //assertion
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json){
                $json
                    ->has("message")
                    ->where("message","charge_code_successfully_applied");
            });
        $userTransactions=$user->transaction;
        $chargeCode=$chargeCode->fresh();
        $userChargeCodes=$user->chargeCodes;
        $this->assertCount(1,$userTransactions);
        $this->assertEquals($userTransactions->first()->amount,$chargeCode->amount);
        $this->assertCount(1,$userChargeCodes);
        $this->assertEquals($userChargeCodes->first()->id,$chargeCode->id);
        $this->assertEquals(1,$chargeCode->usage_count);

    }
    // test for double spending coupon
    public function test_user_can_not_double_apply_the_same_charge_code(){
        //arrange
        $this->seed();
        $user=User::factory()->create();
        $chargeCode=ChargeCode::factory()->create();
        $chargeCode->update(["user_id"=>$user->id]);
        $chargeCode=$chargeCode->fresh();
        $user->chargeCodes()->save($chargeCode);
        $chargeCode->update(["usage_count"=>++$chargeCode->usage_count,"usage_limit"=>10]);
        //act
        $this->postJson("/api/v1/wallet/applyChargeCode",
            [
                "username"=>$user->username,
                "charge_code"=>$chargeCode->code
            ]
        )
            //assertion
            ->assertStatus(400)
            ->assertJson(function (AssertableJson $json){
                $json
                    ->has("message")
                    ->where("message","charge_code_has_been_used");
            });
    }
    public function test_user_can_not_apply_a_charge_code_while_it_is_reached_usage_limit(){
        //arrange
        $this->seed();
        $user=User::factory()->create();
        $chargeCode=ChargeCode::factory()->create();
        $chargeCode->update(["user_id"=>$user->id]);
        $chargeCode=$chargeCode->fresh();
        // simulate that charge code has been reached usage_limit
        $chargeCode->update(["usage_count"=>10,"usage_limit"=>10]);
        //act
        $this->postJson("/api/v1/wallet/applyChargeCode",
            [
                "username"=>$user->username,
                "charge_code"=>$chargeCode->code
            ]
        )
            //assertion
            ->assertStatus(400)
            ->assertJson(function (AssertableJson $json){
                $json
                    ->has("message")
                    ->where("message","charge_code_limit_count_reached");
            });
    }

}
