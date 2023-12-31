<?php

namespace Services\Wallet\Database\Seeders;

use Illuminate\Database\Seeder;
use Services\Wallet\Models\TransactionInformationKeys;

class TransactionInformationKeySeeder extends Seeder
{
    public $keys=[
        "charge_code",
        "charge_code_id",
        "payment_uuid",
        "payment_confirmation_code"
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->keys as $key){
            TransactionInformationKeys::query()->create(
                [
                    "name"=>$key
                ]
            );
        }

    }
}
