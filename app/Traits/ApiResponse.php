<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponse
{
    
    protected function successResponse(mixed $data = null, string $message = null, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    
    protected function errorResponse(string $message = null, int $code = Response::HTTP_INTERNAL_SERVER_ERROR, mixed $details = null): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message ?? 'An error occurred.',
            'errors' => $details,
        ], $code);
    }

    
    protected function validationErrorResponse(mixed $errors, string $message = 'Validation failed.'): JsonResponse
    {
        return $this->errorResponse($message, Response::HTTP_UNPROCESSABLE_ENTITY, $errors);
    }
}
