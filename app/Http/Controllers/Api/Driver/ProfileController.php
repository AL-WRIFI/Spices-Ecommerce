<?php 

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Driver\DriverResource;
use App\Support\Traits\Api\ApiResponseTrait;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    public function fetch(Request $request): JsonResponse
    {
        $user = $request->user();
        return $this->successResponse(data:['driver' => new DriverResource($user)], msg:'successfully');
    }


    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = Auth::user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return $this->errorResponse('Current password is incorrect.', 422);
        }

        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return $this->successResponse(null, 'Password changed successfully.');
    }
}