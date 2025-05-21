<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'image',
    ];

    public function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => asset($value),
        );
    }
}
