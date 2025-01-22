<?php

namespace App\Models;

use App\Enums\OTPStatus;
use App\Enums\OTPType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'otpable_id',
        'otpable_type',
        'type',
        'status',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'type' => OTPType::class,
        'status' => OTPStatus::class,
    ];

    public function otpable()
    {
        return $this->morphTo();
    }
}