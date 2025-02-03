<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtotal',
        'discount_amount',
        'coupon_id',
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

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function activity()
    {
        return $this->hasMany(OrderActivity::class);
    }

    public function subtotal(): Attribute
    {
        return new Attribute(
            get: fn () => $this->orderItems->sum(function ($orderItem) {
                return $orderItem->total_price;
            })
        );
    }

    public function discountAmount(): Attribute
    {
        return new Attribute(
            get: fn () => $this->calculateDiscountAmount()
        );
    }

    public function deliveryAmount(): Attribute
    {
        return new Attribute(
            get: fn () => $this->calculateDeliveryAmount()
        );
    }

    public function totalAmount(): Attribute
    {
        return new Attribute(
            get: fn () => $this->subtotal - $this->discount_amount + $this->delivery_amount
        );
    }

    protected function calculateDiscountAmount()
    {
        if ($this->coupon) {
            $coupon = $this->coupon;

            if (!$coupon->isActive || ($coupon->valid_from && now()->lt($coupon->valid_from)) || ($coupon->valid_to && now()->gt($coupon->valid_to))) {
                return 0;
            }

            if ($coupon->min_amount && $this->subtotal < $coupon->min_amount) {
                return 0; 
            }

            if ($coupon->type === 'fixed') {
                return min($coupon->amount, $this->subtotal);
            } elseif ($coupon->type === 'percent') {
                return ($coupon->amount / 100) * $this->subtotal;
            }
        }

        return 0; 
    }

    protected function calculateDeliveryAmount()
    {
        return 0;
    }
}