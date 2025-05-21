<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug','description','image','status'];


    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => asset($value),
        );
    }

}
