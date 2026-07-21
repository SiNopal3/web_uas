<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $countries = [
            ['name' => 'Germany', 'currency' => 'EUR', 'region' => 'Europe'],
            ['name' => 'China', 'currency' => 'CNY', 'region' => 'Asia'],
            ['name' => 'Indonesia', 'currency' => 'IDR', 'region' => 'Asia'],
            ['name' => 'Australia', 'currency' => 'AUD', 'region' => 'Oceania'],
            ['name' => 'United States', 'currency' => 'USD', 'region' => 'Americas'],
            ['name' => 'Japan', 'currency' => 'JPY', 'region' => 'Asia'],
            ['name' => 'United Kingdom', 'currency' => 'GBP', 'region' => 'Europe'],
            ['name' => 'Singapore', 'currency' => 'SGD', 'region' => 'Asia'],
            ['name' => 'Netherlands', 'currency' => 'EUR', 'region' => 'Europe'],
            ['name' => 'India', 'currency' => 'INR', 'region' => 'Asia'],
        ];

        foreach ($countries as $c) {
            DB::table('countries')->updateOrInsert(
                ['name' => $c['name']],
                ['currency' => $c['currency'], 'region' => $c['region'], 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}