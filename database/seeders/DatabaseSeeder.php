<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Services\Wallet\Database\Seeders\TransactionInformationKeySeeder;
use Services\Wallet\Database\Seeders\TransactionTypeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                TransactionInformationKeySeeder::class,
                TransactionTypeSeeder::class
            ]
        );
    }
}
