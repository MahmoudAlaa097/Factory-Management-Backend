<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\Fault\AcceptFaultAction;
use App\Actions\V1\Fault\ApproveMaintenanceFaultAction;
use App\Actions\V1\Fault\CloseFaultAction;
use App\Actions\V1\Fault\ListFaultsAction;
use App\Actions\V1\Fault\ReportFaultAction;
use App\Actions\V1\Fault\ResolveFaultAction;
use App\Actions\V1\Fault\RespondToFaultAction;
use App\Actions\V1\Fault\ShowFaultAction;
use App\Helpers\QueryScope;
use App\Http\Requests\Api\V1\AcceptFaultRequest;
use App\Http\Requests\Api\V1\ApproveMaintenanceFaultRequest;
use App\Http\Requests\Api\V1\CloseFaultRequest;
use App\Http\Requests\Api\V1\ListFaultsRequest;
use App\Http\Requests\Api\V1\ResolveFaultRequest;
use App\Http\Requests\Api\V1\RespondToFaultRequest;
use App\Http\Requests\Api\V1\ShowFaultRequest;
use App\Http\Requests\Api\V1\StoreFaultRequest;
use App\Http\Resources\V1\FaultResource;
use App\Http\Responses\ApiResponse;
use App\Models\Fault;
use Illuminate\Http\JsonResponse;

class FaultController extends BaseController
{
    public function __construct(
        private ListFaultsAction              $listAction,
        private ShowFaultAction               $showAction,
        private ReportFaultAction             $reportAction,
        private RespondToFaultAction          $respondAction,
        private ResolveFaultAction            $resolveAction,
        private AcceptFaultAction             $acceptAction,
        private ApproveMaintenanceFaultAction $approveAction,
        private CloseFaultAction              $closeAction,
    ) {}

    public function index(ListFaultsRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Fault::class);

        $faults = $this->listAction->execute($request->user());

        return $this->successCollection(
            'Faults retrieved successfully',
            FaultResource::collection($faults),
            $faults
        );
    }

    public function show(ShowFaultRequest $request, int $id): JsonResponse
    {
        $fault = $this->findScopedFault($request->user(), $id, [
            'machine', 'technicians', 'components'
        ]);

        $this->authorize('view', $fault);

        $fault = $this->showAction->execute($fault);

        return $this->successResource(
            'Fault retrieved successfully',
            new FaultResource($fault)
        );
    }

    public function store(StoreFaultRequest $request): JsonResponse
    {
        $this->authorize('create', Fault::class);

        $fault = $this->reportAction->execute($request, $request->user());

        return ApiResponse::success(
            'Fault reported successfully',
            new FaultResource($fault),
            null,
            201
        );
    }

    public function respond(RespondToFaultRequest $request, int $id): JsonResponse
    {
        $fault = $this->findScopedFault($request->user(), $id);

        $this->authorize('respond', $fault);

        $fault = $this->respondAction->execute($request, $fault, $request->user());

        return $this->successAction('Fault is now in progress', $fault);
    }

    public function resolve(ResolveFaultRequest $request, int $id): JsonResponse
    {
        $fault = $this->findScopedFault($request->user(), $id);

        $this->authorize('resolve', $fault);

        $fault = $this->resolveAction->execute($fault);

        return $this->successAction('Fault resolved successfully', $fault);
    }

    public function accept(AcceptFaultRequest $request, int $id): JsonResponse
    {
        $fault = $this->findScopedFault($request->user(), $id);

        $this->authorize('accept', $fault);

        $fault = $this->acceptAction->execute($fault);

        return $this->successAction('Fault accepted successfully', $fault);
    }

    public function approve(ApproveMaintenanceFaultRequest $request, int $id): JsonResponse
    {
        $fault = $this->findScopedFault($request->user(), $id);

        $this->authorize('approve', $fault);

        $fault = $this->approveAction->execute($fault, $request->user());

        return $this->successAction('Fault approved by maintenance successfully', $fault);
    }

    public function close(CloseFaultRequest $request, int $id): JsonResponse
    {
        $fault = $this->findScopedFault($request->user(), $id);

        $this->authorize('close', $fault);

        $fault = $this->closeAction->execute($fault, $request->user());

        return $this->successAction('Fault closed successfully', $fault);
    }

    private function findScopedFault($user, int $id, array $with = []): Fault
    {
        return QueryScope::faults($user)
            ->when($with, fn ($q) => $q->with($with))
            ->findOrFail($id);
    }

    private function successAction(string $message, Fault $fault): JsonResponse
    {
        return ApiResponse::success(
            $message,
            new FaultResource($fault)
        );
    }
}
