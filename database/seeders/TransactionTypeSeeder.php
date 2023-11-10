<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
