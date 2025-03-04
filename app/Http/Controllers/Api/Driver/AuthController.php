<?php 

namespace App\Http\Controllers\Api\Driver;

use App\Actions\Otp\SendOtpAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Driver;
use App\Models\User;
use App\Support\Services\Otp\OtpService;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    use ApiResponseTrait;

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = Driver::where('phone', $data['phone'])->first();

        if (!$user) {
            return $this->notFoundResponse(msg: __('User is not found with this email! Please sign up'));
        }

        if (!Hash::check($data['password'], $user->password)) {
            return $this->errorResponse(msg: __('Invalid credentials'), code: 401);
        }

        if ($user->status != 'active') {
            return $this->errorResponse(msg: __('User is not active'), code: 401);
        }

        return $this->successResponse(data: [
            'token' => $user->createToken('user_token')->plainTextToken,
            'user' => $user,
        ], msg: __('User logged in successfully'));
    }
}