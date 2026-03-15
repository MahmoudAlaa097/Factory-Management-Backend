<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponse
{
    public static function success(
        string $message,
        mixed $data = [],
        mixed $paginator = null,
        int $statusCode = 200
    ): JsonResponse {
        $response = [
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ];

        if ($paginator instanceof LengthAwarePaginator) {
            $response['meta'] = [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
            ];
        }

        return response()->json($response, $statusCode);
    }

    public static function error(string $message, int $statusCode, array $errors = []): JsonResponse
    {
        $response = [
            'status'  => 'error',
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    public static function validationError(string $message, array $errors): JsonResponse
    {
        return self::error($message, 422, $errors);
    }

    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error($message, 404);
    }

    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, 401);
    }

    public static function forbidden(string $message = 'You are not authorized to perform this action'): JsonResponse
    {
        return self::error($message, 403);
    }

    public static function serverError(string $message = 'Something went wrong, please try again'): JsonResponse
    {
        return self::error($message, 500);
    }
}
