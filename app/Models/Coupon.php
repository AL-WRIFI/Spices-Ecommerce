<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'amount',
        'min_amount',
        'max_discount_amount',
        'status',
        'usage_limit',
        'used_count',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];


    public function isActive()
    {
        return $this->status == "active" ? true : false;
    }


    public function isValid()
    {
        if (!$this->isActive()) {
            return false;
        }

        $now = now();
        
        if ($this->expiry_date && $now->greaterThan($this->expiry_date)) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }


    public function incrementUsage()
    {
        if ($this->isValid()) {
            $this->increment('used_count');
        } else {
            throw new \Exception("الكوبون غير صالح ولا يمكن استخدامه.");
        }
    }

    public function isFullyUsed()
    {
        return $this->usage_limit !== null && $this->used_count >= $this->usage_limit;
    }
}