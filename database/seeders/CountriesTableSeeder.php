<?php

namespace Database\Seeders;

use App\Models\Location\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Yemen', 'code' => 'YE', 'status' => 'active'],
            ['name' => 'Saudi Arabia', 'code' => 'SA', 'status' => 'active'],
            ['name' => 'United Arab Emirates', 'code' => 'AE', 'status' => 'active'],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
