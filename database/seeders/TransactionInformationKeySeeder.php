<?php

namespace Database\Seeders;

use App\Models\TransactionInformationKeys;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionInformationKeySeeder extends Seeder
{
    public $keys=[
        "coupon_code",
        "coupon_id",
        "payment_uuid",
        "payment_confirmation_code"
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->keys as $key){
            TransactionInformationKeys::query()->insert(
                [
                    "name"=>$key
                ]
            );
        }

    }
}
