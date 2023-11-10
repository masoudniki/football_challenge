<?php

namespace Database\Seeders;

use App\Models\TransactionInformationKeys;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
