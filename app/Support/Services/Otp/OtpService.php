<?php 

namespace App\Support\Services\Otp;

use App\Enums\OTPStatusEnum;
use App\Enums\OTPTypeEnum;
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
    public function expiredPreviousCodes(Model $model, OTPTypeEnum $OTPTypeEnum)
    {
        return $model->otps()->where('type', $OTPTypeEnum)->update(['expired_at' => now(), 'status' => OTPStatusEnum::EXPIRED]);
    }

    public function deleteExpiredCodes()
    {
        return $this->otp::where('expired_at', '<', now())->delete();
    }

    public function chackOtp(Model $model, OTPTypeEnum $OTPTypeEnum, $otp = null)
    {
        return $model->otps()->where('type', $OTPTypeEnum->value)->where('expired_at', '>', now())->where('code', $otp)->select('id', 'code')->first();
    }

    public function getSameTypeOtp(Model $model, OTPTypeEnum $OTPTypeEnum)
    {
        return $model->otps()->where('type', $OTPTypeEnum->value)->where('expired_at', '>', now())->where('status', OTPStatusEnum::ACTIVE)->select('id', 'code')->first();
    }
}