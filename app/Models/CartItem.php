<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'product_id', 'quantity'];

    protected $casts = [
        'item_price' => 'float',
        'total_price' => 'float'
    ];
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function itemPrice(): Attribute
    {
        return new Attribute(
            get: fn() => $this->product->price,
        );
    }

    public function totalPrice(): Attribute
    {
        return new Attribute(
            get: fn() => $this->item_price * $this->quantity
        );
    }
}
