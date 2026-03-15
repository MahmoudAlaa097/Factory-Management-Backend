<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;


abstract class BaseController extends Controller
{
    use AuthorizesRequests;

    protected function successCollection(
        string $message,
        ResourceCollection $collection,
        LengthAwarePaginator $paginator
    ): JsonResponse {
        return ApiResponse::success($message, $collection, $paginator);
    }

    protected function successResource(
        string $message,
        JsonResource $resource
    ): JsonResponse {
        return ApiResponse::success($message, $resource);
    }
}
