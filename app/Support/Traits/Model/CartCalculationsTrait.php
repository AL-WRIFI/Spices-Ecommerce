<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait CartCalculationsTrait
{
  
    public function subtotal(): Attribute
    {
        return new Attribute(
            get: fn () => $this->cartItems->sum(fn ($item) => $item->total_price)
        );
    }

    public function deliveryAmount(): Attribute
    {
        return new Attribute(
            get: fn () => $this->calculateDeliveryAmount()
        );
    }

    public function discountAmount(): Attribute
    {
        return new Attribute(
            get: fn () => $this->calculateDiscountAmount()
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


}