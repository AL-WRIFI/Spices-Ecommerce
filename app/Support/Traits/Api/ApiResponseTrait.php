<?php

namespace App\Support\Traits\Api;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    protected function successResponse($data = null, string $msg = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $msg,
            'data' => $data,
        ], $code);
    }

    protected function errorResponse(string $msg = '', int $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $msg,
            'errors' => $errors,
        ], $code);
    }

    protected function notFoundResponse(string $msg = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($msg, 404);
    }

    protected function unauthorizedResponse(string $msg = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($msg, 401);
    }

    protected function validationErrorResponse($errors, string $msg = 'Validation failed'): JsonResponse
    {
        return $this->errorResponse($msg, 422, $errors);
    }
}