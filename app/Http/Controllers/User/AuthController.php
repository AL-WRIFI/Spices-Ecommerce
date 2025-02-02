<?php 

namespace App\Http\Controllers\User;

use App\Actions\Otp\SendOtpAction;
use App\Enums\OTPTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChackOtpRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Support\Services\Otp\OtpService;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    use ApiResponseTrait;

    protected OtpService $otpService;
    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::where('phone', $data['phone'])->first();

        if (!$user) {
            return $this->notFoundResponse(msg: __('User is not found with this phone! Please sign up'));
        }

        if (!Hash::check($data['password'], $user->password)) {
            return $this->errorResponse(msg: __('Invalid credentials'), code: 401);
        }

        if ($user->status != 1) {
            return $this->errorResponse(msg: __('User is not active'), code: 401);
        }

        // $sendOtpAction->handle(model:$user, OTPTypeEnum:OTPTypeEnum::LOGIN, expiredMinutes:5);

       // return $this->successResponse(msg:__('OTP Send Successfully'));

        return $this->successResponse(data: [
            'token' => $user->createToken('user_token')->plainTextToken,
            'user' => $user,
        ], msg: __('User logged in successfully'));
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => "alwrifi@g.com",
            'password' => Hash::make($data['password']),
        ]);

        return $this->successResponse(data:[
            'token' => $user->createToken('user-token')->plainTextToken,
            'user' => $user,
        ]);
    }
    public function verifyOtp(ChackOtpRequest $request)
    {
        $data = $request->validated();
        $user = User::where('phone', $data['phone']);

        $OTPStatusEnum = $this->otpService->chackOtp($user, OTPTypeEnum::LOGIN, $data['code']);
        if(!$OTPStatusEnum) return $this->errorResponse(msg:__('OTP is valid'), code:401);

        return $this->successResponse(data:[
            'token' => $user->createToken('user_token')->plainTextToken,
            'user' => $user,
        ], msg:__('user verified'));
    }
}