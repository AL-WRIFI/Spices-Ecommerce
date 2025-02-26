<?php 

namespace App\Actions\Otp;

use App\Enums\Otp\OTPTypeEnum;
use App\Jobs\SendOtpQueue;
use App\Support\Services\Otp\OtpService;
use Illuminate\Database\Eloquent\Model;


class SendOtpAction
{
    protected OtpService $otpService;
    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function handle(Model $model, OTPTypeEnum $OTPTypeEnum, int $expiredMinutes)
    {
        $code = rand(1000, 9999);
        $payload = ['code' => $code, 'type' => $OTPTypeEnum, 'expired_at' => now()->addMinutes($expiredMinutes)];

        $haveOldCode = $this->otpService->getSameTypeOtp($model, $OTPTypeEnum);
        if($haveOldCode) return false;

        $this->otpService->send($model?->phone, $code." : جزيرة العطاء| رمز التحقق");
        // dispatch(new SendOtpQueue($model, __('Your Otp is :').$code));

        return $this->otpService->createForModel($model, $payload);
    }
}