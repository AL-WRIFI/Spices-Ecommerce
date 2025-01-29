<?php

namespace App\Models;

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

    public function itemPrice()
    {
        return $this->product->price;
    }

    public function totalPrice()
    {
        return $this->item_price * $this->quantity;
    }
}
