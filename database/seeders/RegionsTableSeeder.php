<?php

namespace Database\Seeders;

use App\Models\Location\Country;
use App\Models\Location\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $yemen = Country::where('name', 'Yemen')->first();

        $regions = [
            ['name' => 'Sana\'a', 'country_id' => $yemen->id, 'status' => 'active'],
            ['name' => 'Aden', 'country_id' => $yemen->id, 'status' => 'active'],
            ['name' => 'Taiz', 'country_id' => $yemen->id, 'status' => 'active'],
            ['name' => 'Hodeidah', 'country_id' => $yemen->id, 'status' => 'active'],
            ['name' => 'Ibb', 'country_id' => $yemen->id, 'status' => 'active'],
            ['name' => 'Hadramaut', 'country_id' => $yemen->id, 'status' => 'active'],
            ['name' => 'Al Mahwit', 'country_id' => $yemen->id, 'status' => 'active'],
            ['name' => 'Dhamar', 'country_id' => $yemen->id, 'status' => 'active'],
            ['name' => 'Al Hudaydah', 'country_id' => $yemen->id, 'status' => 'active'],
            ['name' => 'Marib', 'country_id' => $yemen->id, 'status' => 'active'],
        ];

        foreach ($regions as $region) {
            Region::create($region);
        }
    }
}
