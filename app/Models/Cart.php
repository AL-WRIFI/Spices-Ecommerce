<?php

namespace App\Models;

use App\Support\Traits\Model\DistanceTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory, DistanceTrait;

    protected $fillable = ['user_id', 'coupon_id'];

    protected $casts = [
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'delivery_amount' => 'float',
        'total_amount' => 'float'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function subtotal(): Attribute
    {
        return new Attribute(
            get: fn () => $this->cartItems->sum(fn ($item) => $item->total_price)
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
        if (!$this->isCouponValid()) {
            return 0;
        }

        $baseDiscount = $this->calculateBaseDiscount();

        return $this->applyMaxDiscountLimit($baseDiscount);
    }

    private function isCouponValid()
    {
        return $this->coupon && $this->coupon->isValid() && $this->subtotal >= $this->coupon->min_amount;
    }

    private function calculateBaseDiscount()
    {
        return match ($this->coupon->type) {
            'fixed' => $this->coupon->amount,
            'percentage' => ($this->subtotal * $this->coupon->amount) / 100,
            default => 0,
        };
    }

    private function applyMaxDiscountLimit($discount)
    {
        if ($this->coupon->max_discount_amount && $discount > $this->coupon->max_discount_amount) {
            return $this->coupon->max_discount_amount;
        }
        return $discount;
    }

    private function calculateDeliveryAmount()
    {
        return round($this->distance(), 2);
    }

    public function distance()
    {
        $userLatitude = $this->user?->latitude ?? 24.24522;
        $userLongitude = $this->user?->longitude ?? 43.23452;

        $storeLatitude = 23.34342;
        $storeLongitude = 23.34563;

        if (!$userLatitude || !$userLongitude || !$storeLatitude || !$storeLongitude) {
            return 0; 
        }

        return $this->calculateDistance(
            $userLatitude,
            $userLongitude,
            $storeLatitude,
            $storeLongitude
        );
    }
}
