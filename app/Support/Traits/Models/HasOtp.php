<?php 

namespace App\Support\Traits\Models;

use App\Models\Otp;

trait HasOtp
{
    public function otps() 
    {
        return $this->morphMany(Otp::class, 'otpable');
    }
}