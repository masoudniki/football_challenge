<?php

namespace Tests\Feature;

use App\Models\CouponCode;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class WalletControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_not_apply_coupon_when_username_does_not_exists(){
        $this
            ->postJson("/api/v1/wallet/applyCoupon",[
                "username"=>"+989011111111",
                "coupon_code"=>"tktkt",
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
    public function test_user_can_not_apply_coupon_when_username_does_not_match_with_regex(){
        $this
            ->postJson("/api/v1/wallet/applyCoupon",[
                "username"=>"0901",
                "coupon_code"=>"tktkt",
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
    public function test_user_can_not_apply_coupon_when_coupon_does_not_exists(){
        //arrange
        $user=User::factory()->create();
        //act
        $this
            ->postJson("/api/v1/wallet/applyCoupon",[
                "username"=>$user->username,
                "coupon_code"=>"tktkt",
            ])

            //assertions
            ->assertStatus(422)
            ->assertJson(function (AssertableJson $json){
                $json
                    ->has("message")
                    ->has("errors")
                    ->has("errors.coupon_code",1)
                    ->where("errors.coupon_code.0","The selected coupon code is invalid.");
            });
    }
    public function test_user_can_apply_coupon_successfully(){
        //arrange
        $this->seed();
        $user=User::factory()->create();
        $coupon=CouponCode::factory()->create();
        //act
        $this->postJson("/api/v1/wallet/applyCoupon",
            [
                "username"=>$user->username,
                "coupon_code"=>$coupon->code
            ]
        )
            //assertion
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json){
                $json
                    ->has("message")
                    ->where("message","coupon_successfully_applied");
            });
        $userTransactions=$user->transaction;
        $coupon=$coupon->fresh();
        $this->assertCount(1,$userTransactions);
        $this->assertEquals($userTransactions->first()->amount,$coupon->amount);
        $this->assertEquals($coupon->user_id,$user->id);

    }
    // test for double spending coupon
    public function test_user_can_not_apply_a_coupon_while_the_coupon_is_attached_to_another_user(){
        //arrange
        $this->seed();
        $user=User::factory()->create();
        $coupon=CouponCode::factory()->create();
        $coupon->update(["user_id"=>$user->id]);
        $coupon=$coupon->fresh();
        //act
        $this->postJson("/api/v1/wallet/applyCoupon",
            [
                "username"=>$user->username,
                "coupon_code"=>$coupon->code
            ]
        )
            //assertion
            ->assertStatus(400)
            ->assertJson(function (AssertableJson $json){
                $json
                    ->has("message")
                    ->where("message","coupon_has_been_used");
            });

    }

}
