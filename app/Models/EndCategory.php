<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug','sub_category_id','description','image','status'];


    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }
}
