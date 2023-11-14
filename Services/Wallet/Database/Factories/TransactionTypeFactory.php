<?php

namespace Services\Wallet\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Services\Wallet\Models\TransactionType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Services\Wallet\Models\TransactionType>
 */
class TransactionTypeFactory extends Factory
{
    protected $model=TransactionType::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name"=>fake()->shuffleArray(["charge_code","payment"])
        ];
    }
}
