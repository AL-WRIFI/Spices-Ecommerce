<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_price',
        'quantity',
    ];

    protected $casts = [
        'product_price' => 'float',
        'total_price' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productPrice(): Attribute
    {
        return new Attribute(
            get: fn() => $this->product->price,
        );
    }

    public function totalPrice(): Attribute
    {
        return new Attribute(
            get: fn() => $this->product_price * $this->quantity
        );
    }
}