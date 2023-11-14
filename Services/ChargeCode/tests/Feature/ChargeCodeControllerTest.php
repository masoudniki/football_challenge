<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Services\ChargeCode\Models\ChargeCode;
use Tests\TestCase;

class ChargeCodeControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_not_get_charge_code_when_charge_code_does_not_exists(){
        $this->getJson("/api/v1/charge-code/reports/some_invalid_charge_code")
            ->assertStatus(404);
    }
    public function test_user_get_specific_charge_code_report_successfully(){
        //arrange
        $this->seed();
        $users=User::factory()->count(10)->create();
        $chargeCode=ChargeCode::factory()->create();
        $chargeCode->users()->sync($users->pluck("id"));
        //act
        $this
            ->getJson("/api/v1/charge-code/reports/".$chargeCode->code)
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json){
                $json
                    ->has("data")
                    ->has("data",function (AssertableJson $json){
                        $json
                            ->has("code")
                            ->has("amount")
                            ->has("usage_limit")
                            ->has("usage_count")
                            ->has("used_by",10)
                            ->has("used_by.0",function (AssertableJson $json){
                                $json
                                    ->has("username")
                                    ->has("name");
                            });
                    });
            });
    }
    public function test_user_get_all_charge_code_reports(){
        //arrange
        $this->seed();
        $users=User::factory()->count(10)->create();
        $chargeCode1=ChargeCode::factory()->create();
        $chargeCode1->users()->sync($users->pluck("id"));
        $chargeCode2=ChargeCode::factory()->create();
        $chargeCode2->users()->sync($users->pluck("id"));
        $chargeCode3=ChargeCode::factory()->create();
        $chargeCode3->users()->sync($users->pluck("id"));

        //act
        $this
            ->getJson("/api/v1/charge-code/reports")
            //assertions
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json){
                $json
                    ->has("data",3)
                    ->has("data.0",function (AssertableJson $json){
                        $json
                            ->has("code")
                            ->has("amount")
                            ->has("usage_limit")
                            ->has("usage_count");
                    });
            });
    }
}
