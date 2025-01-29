<?php 

namespace App\Http\Controllers;

use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Location\District;
use App\Models\Location\Region;



class LocationController extends Controller
{
    public function getCountries() 
    {
        return response()->json(Country::all());
    }
    public function getRegions($country_id)
    {
        return response()->json(Region::where('country_id', $country_id)->get());
    }

    public function getCities($region_id)
    {
        return response()->json(City::where('region_id', $region_id)->get());
    }

    public function getDistricts($city_id)
    {
        return response()->json(District::where('city_id', $city_id)->get());
    }
}