<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderActivity extends Model
{
    use HasFactory;

    protected $fillable = ['activity', 'activity_date', 'order_id', 'order_status'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
