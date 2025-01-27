<?php

namespace Database\Seeders;

use App\Models\Location\City;
use App\Models\Location\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sanaaCity = City::where('name', 'Sana\'a City')->first();
        $adenCity = City::where('name', 'Aden City')->first();
        $taizCity = City::where('name', 'Taiz City')->first();

        $districts = [
            ['name' => 'حي النزهة', 'city_id' => $sanaaCity->id, 'status' => 'active'],
            ['name' => 'حي الروضة', 'city_id' => $sanaaCity->id, 'status' => 'active'],
            ['name' => 'حي التحرير', 'city_id' => $sanaaCity->id, 'status' => 'active'],

            ['name' => 'حي كريتر', 'city_id' => $adenCity->id, 'status' => 'active'],
            ['name' => 'حي المعلا', 'city_id' => $adenCity->id, 'status' => 'active'],
            ['name' => 'حي الشيخ عثمان', 'city_id' => $adenCity->id, 'status' => 'active'],

            ['name' => 'حي المظفر', 'city_id' => $taizCity->id, 'status' => 'active'],
            ['name' => 'حي القاهرة', 'city_id' => $taizCity->id, 'status' => 'active'],
            ['name' => 'حي السلام', 'city_id' => $taizCity->id, 'status' => 'active'],
        ];

        foreach ($districts as $district) {
            District::create($district);
        }
    }
}
