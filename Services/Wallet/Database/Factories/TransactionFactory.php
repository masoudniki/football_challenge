<?php

namespace Services\Wallet\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Services\Wallet\Models\Transaction;
use Services\Wallet\Models\TransactionType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Services\Wallet\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model=Transaction::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id"=>User::factory(),
            "type_id"=>TransactionType::factory(),
            "amount"=>fake()->randomNumber(9)
        ];
    }
}
