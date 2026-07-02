<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run()
    {
        DB::table('countries')->insert([
            ['name' => 'Germany', 'currency' => 'EUR', 'region' => 'Europe'],
            ['name' => 'China', 'currency' => 'CNY', 'region' => 'Asia'],
            ['name' => 'Indonesia', 'currency' => 'IDR', 'region' => 'Asia'],
            ['name' => 'Australia', 'currency' => 'AUD', 'region' => 'Oceania'],
        ]);
    }
}