<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subtotal' => $this->subtotal,
            'discount_amount' => $this->discount_amount,
            'delivery_amount' => $this->delivery_amount,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'shipping_address' => $this->shipping_address,
            'payment_method' => $this->payment_method,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
