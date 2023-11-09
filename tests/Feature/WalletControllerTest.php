<?php

namespace Tests\Feature;

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
                "username"=>"09011231111",
                "coupon_code"=>"tktkt",
            ])
            ->assertStatus(422)
            ->assertJson(function (AssertableJson $json){
                $json
                    ->has("message")
                    ->has("errors")
                    ->has("errors.username");
            });
    }
}
