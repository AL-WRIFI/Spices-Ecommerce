<?php

namespace App\Models;

use App\Enums\Otp\OTPStatusEnum;
use App\Enums\Otp\OTPTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Otp extends Model
{
    use HasFactory;
    protected $fillable = ['code','otpable_id','otpable_type','type','status','expired_at'];

    protected $casts = [
        'expired_at' => 'datetime',
        'type' => OTPTypeEnum::class,
        'status' => OTPStatusEnum::class,
    ];

    protected $attributes = [
        'status' => OTPStatusEnum::ACTIVE,
    ];

    public function otpable(): MorphTo
    {
        return $this->morphTo();
    }
}