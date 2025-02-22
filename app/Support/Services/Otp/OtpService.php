<?php 

namespace App\Support\Services\Otp;

use App\Enums\Otp\OTPStatusEnum;
use App\Enums\Otp\OTPTypeEnum;
use App\Models\Otp;
use App\Support\Services\Otp\Provider\Alawaeltec;
use Illuminate\Database\Eloquent\Model;


class OTPService
{
    protected Otp $otp;
    protected Alawaeltec $alawaeltec;

    public function __construct(Otp $otp, Alawaeltec $alawaeltec)
    {
        $this->otp = $otp;
        $this->alawaeltec = $alawaeltec;
    }

    public function createForModel(Model $model, array $payload)
    {
        return $model->otp()->create($payload);
    }
    public function expiredPreviousCodes(Model $model, OTPTypeEnum $OTPTypeEnum)
    {
        return $model->otp()->where('type', $OTPTypeEnum)->update(['expired_at' => now(), 'status' => OTPStatusEnum::EXPIRED]);
    }

    public function deleteExpiredCodes()
    {
        return $this->otp::where('expired_at', '<', now())->delete();
    }

    public function chackOtp(Model $model, OTPTypeEnum $OTPTypeEnum, $otp = null)
    {
        return $model->otp()->where('type', $OTPTypeEnum->value)->where('expired_at', '>', now())->where('code', $otp)->select('id', 'code')->first();
    }

    public function getSameTypeOtp(Model $model, OTPTypeEnum $OTPTypeEnum)
    {
        return $model->otp()->where('type', $OTPTypeEnum->value)->where('expired_at', '>', now())->where('status', OTPStatusEnum::ACTIVE)->select('id', 'code')->first();
    }

    public function send($phone_number, $message)
    {
        return $this->alawaeltec->send($phone_number, $message);
    }
}