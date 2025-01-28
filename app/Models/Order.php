<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtotal',
        'discount_amount',
        'delivery_amount',
        'total_amount',
        'status',
        'user_id',
        'coupon',
        'shipping_address',
        'payment_method',
        'payment_status',
        'driver_appointed',
        'driver_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}