<?php

namespace Database\Seeders;

use App\Models\Location\City;
use App\Models\Location\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $sanaa = Region::where('name', 'Sana\'a')->first();
        $aden = Region::where('name', 'Aden')->first();
        $taiz = Region::where('name', 'Taiz')->first();
        $hodeidah = Region::where('name', 'Hodeidah')->first();
        $ibb = Region::where('name', 'Ibb')->first();
        $hadramaut = Region::where('name', 'Hadramaut')->first();
        $alMahwit = Region::where('name', 'Al Mahwit')->first();
        $dhamar = Region::where('name', 'Dhamar')->first();
        $alHudaydah = Region::where('name', 'Al Hudaydah')->first();
        $marib = Region::where('name', 'Marib')->first();

        $cities = [
            ['name' => 'Sana\'a City', 'region_id' => $sanaa->id, 'status' => 'active'],
            ['name' => 'Bani Al-Harith', 'region_id' => $sanaa->id, 'status' => 'active'],
            ['name' => 'Al-Hasaba', 'region_id' => $sanaa->id, 'status' => 'active'],

            ['name' => 'Aden City', 'region_id' => $aden->id, 'status' => 'active'],
            ['name' => 'Al-Mansoura', 'region_id' => $aden->id, 'status' => 'active'],
            ['name' => 'Crater', 'region_id' => $aden->id, 'status' => 'active'],

            ['name' => 'Taiz City', 'region_id' => $taiz->id, 'status' => 'active'],
            ['name' => 'Al-Mawasit', 'region_id' => $taiz->id, 'status' => 'active'],
            ['name' => 'Al-Misrakh', 'region_id' => $taiz->id, 'status' => 'active'],

            ['name' => 'Hodeidah City', 'region_id' => $hodeidah->id, 'status' => 'active'],
            ['name' => 'Bajil', 'region_id' => $hodeidah->id, 'status' => 'active'],
            ['name' => 'Zabid', 'region_id' => $hodeidah->id, 'status' => 'active'],

            ['name' => 'Ibb City', 'region_id' => $ibb->id, 'status' => 'active'],
            ['name' => 'Yarim', 'region_id' => $ibb->id, 'status' => 'active'],
            ['name' => 'Ba\'dan', 'region_id' => $ibb->id, 'status' => 'active'],

            ['name' => 'Mukalla', 'region_id' => $hadramaut->id, 'status' => 'active'],
            ['name' => 'Seiyun', 'region_id' => $hadramaut->id, 'status' => 'active'],
            ['name' => 'Tarim', 'region_id' => $hadramaut->id, 'status' => 'active'],

            ['name' => 'Al Mahwit City', 'region_id' => $alMahwit->id, 'status' => 'active'],
            ['name' => 'Shaharah', 'region_id' => $alMahwit->id, 'status' => 'active'],
            ['name' => 'Al Khabt', 'region_id' => $alMahwit->id, 'status' => 'active'],

            ['name' => 'Dhamar City', 'region_id' => $dhamar->id, 'status' => 'active'],
            ['name' => 'Utmah', 'region_id' => $dhamar->id, 'status' => 'active'],
            ['name' => 'Jahran', 'region_id' => $dhamar->id, 'status' => 'active'],

            ['name' => 'Al Hudaydah City', 'region_id' => $alHudaydah->id, 'status' => 'active'],
            ['name' => 'Zabid', 'region_id' => $alHudaydah->id, 'status' => 'active'],
            ['name' => 'Bajil', 'region_id' => $alHudaydah->id, 'status' => 'active'],

            ['name' => 'Marib City', 'region_id' => $marib->id, 'status' => 'active'],
            ['name' => 'Al-Jubah', 'region_id' => $marib->id, 'status' => 'active'],
            ['name' => 'Raghwan', 'region_id' => $marib->id, 'status' => 'active'],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
