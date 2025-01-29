<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'amunt',
        'min_amount',
        'max_discount_amount',
        'valid_from',
        'valid_to',
        'status'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
    ];


    public function isActive()
    {
        return $this->status == "active" ? true : false;
    }
}