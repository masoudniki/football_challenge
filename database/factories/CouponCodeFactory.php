<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CouponCode>
 */
class CouponCodeFactory extends Factory
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
            "amount"=>fake()->randomDigitNotZero()
        ];
    }
}
