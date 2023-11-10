<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChargeCode>
 */
class ChargeCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "code"=>fake()->unique()->regexify("/[A-Za-z0-9]{20}/"),
            "amount"=>fake()->randomDigitNotZero(),
            "usage_limit"=>fake()->randomDigitNotZero(),
            "usage_count"=>0
        ];
    }
}
