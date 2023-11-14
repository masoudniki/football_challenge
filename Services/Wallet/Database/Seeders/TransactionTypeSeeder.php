<?php

namespace Services\Wallet\Database\Seeders;

use Illuminate\Database\Seeder;
use Services\Wallet\Models\TransactionType;

class TransactionTypeSeeder extends Seeder
{
    public $types=[
        "charge_code",
        "payment"
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->types as $type){
            TransactionType::query()->create([
                "name"=>$type
            ]);
        }
    }
}
