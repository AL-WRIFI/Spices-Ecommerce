<?php 

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
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

    public function show(): JsonResponse
    {
        $user = Auth::user();
        return $this->successResponse($user, 'User profile retrieved successfully.');
    }

    public function update(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:users,email,' . Auth::id(),
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|min:-90|max:90',
            'longitude' => 'nullable|numeric|min:-180|max:180'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = Auth::user();

        $user->update($request->only([
            'name', 'phone', 'email', 'address', 'latitude', 'longitude'
        ]));

        return $this->successResponse($user, 'User profile updated successfully.');
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