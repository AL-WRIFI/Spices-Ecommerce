<?php 

namespace App\Support\Services\Otp;

use App\Enums\OTPStatus;
use App\Enums\OTPType;
use App\Models\Otp;
use Illuminate\Database\Eloquent\Model;


class OtpService
{
    protected Otp $otp;
    public function __construct(Otp $otp)
    {
        $this->otp = $otp;
    }
    
    public function createForModel(Model $model, array $payload)
    {
        return $model->create($payload);
    }
    public function expiredPreviousCodes(Model $model, OTPType $OTPType)
    {
        return $model->otps()->where('type', $OTPType)->update(['expired_at' => now(), 'status' => OTPStatus::EXPIRED]);
    }

    public function deleteExpiredCodes()
    {
        return $this->otp::where('expired_at', '<', now())->delete();
    }

    public function chackOtp(Model $model, OTPType $OTPType, $otp = null)
    {
        return $model->otps()->where('type', $OTPType->value)->where('expired_at', '>', now())->where('code', $otp)->select('id', 'code')->first();
    }

    public function getSameTypeOtp(Model $model, OTPType $OTPType)
    {
        return $model->otps()->where('type', $OTPType->value)->where('expired_at', '>', now())->where('status', OTPStatus::ACTIVE)->select('id', 'code')->first();
    }
}