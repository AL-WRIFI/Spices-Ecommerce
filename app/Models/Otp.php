<?php

namespace App\Models;

use App\Enums\OTPStatusEnum;
use App\Enums\OTPTypeEnum;
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
        'type' => OTPTypeEnum::class,
        'status' => OTPStatusEnum::class,
    ];

    public function otpable()
    {
        return $this->morphTo();
    }
}