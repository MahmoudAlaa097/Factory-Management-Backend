<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Responses\ApiResponse;
use App\Models\Dashboard;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    public function __construct(private DashboardService $service) {}

    public function kpis(Request $request): JsonResponse
    {
        $this->authorize('view', Dashboard::class);

        $dateFrom = $request->date('date_from', 'Y-m-d') ?? now()->startOfMonth();
        $dateTo   = $request->date('date_to', 'Y-m-d')   ?? now()->endOfDay();

        return ApiResponse::success(
            'Dashboard KPIs',
            $this->service->kpis($request->user(), $dateFrom, $dateTo)
        );
    }

    public function machines(Request $request): JsonResponse
    {
        $this->authorize('view', Dashboard::class);

        $dateFrom = $request->date('date_from', 'Y-m-d') ?? now()->startOfMonth();
        $dateTo   = $request->date('date_to', 'Y-m-d')   ?? now()->endOfDay();

        return ApiResponse::success(
            'Dashboard machines',
            $this->service->machines($request->user(), $dateFrom, $dateTo)
        );
    }

    public function technicians(Request $request): JsonResponse
    {
        $this->authorize('view', Dashboard::class);

        return ApiResponse::success(
            'Dashboard technicians',
            $this->service->technicians($request->user())
        );
    }

    public function activity(Request $request): JsonResponse
    {
        $this->authorize('view', Dashboard::class);

        return ApiResponse::success(
            'Dashboard activity',
            $this->service->activity($request->user())
        );
    }
}
