<?php

namespace App\Models;

use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Location\District;
use App\Models\Location\Region;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'salary',
        'image',
        'identity_image',
        'identity_number',
        'iban',
        'has_order',
        'country_id',
        'region_id',
        'city_id',
        'district_id',
        'address',
        'status',
    ];


    protected $casts = [
        'has_order' => 'boolean',
        'salary' => 'decimal',
    ];

    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}