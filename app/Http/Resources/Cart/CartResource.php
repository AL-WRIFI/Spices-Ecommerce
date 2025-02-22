<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'subtotal' => $this->subtotal,
            'user_id' => $this->user_id,
            'coupon' => $this->coupon?->code,
            'total_amount' => $this->total_amount,
            'discount_amount' => $this->discount_amount ?? 0,
            'distance' => $this->distance() ?? 0,
            'delivery_amount' => $this->delivery_amount ?? 0,
            'created_at' => $this->created_at,
            'items' => CartItemResource::collection($this->cartItems),
        ];
    }
}