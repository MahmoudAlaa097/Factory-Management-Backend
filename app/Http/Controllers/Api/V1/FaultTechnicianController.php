<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\Fault\AssignTechnicianAction;
use App\Actions\V1\Fault\UnassignTechnicianAction;
use App\Http\Requests\Api\V1\StoreFaultTechnicianRequest;
use App\Http\Resources\V1\FaultResource;
use App\Http\Responses\ApiResponse;
use App\Models\Employee;
use App\Models\Fault;
use Illuminate\Http\JsonResponse;

class FaultTechnicianController extends BaseController
{
    public function __construct(
        private AssignTechnicianAction   $assignAction,
        private UnassignTechnicianAction $unassignAction,
    ) {}

    public function store(StoreFaultTechnicianRequest $request, Fault $fault): JsonResponse
    {
        $fault = $this->assignAction->execute($request, $fault);

        return ApiResponse::success(
            'Technician assigned successfully',
            new FaultResource($fault),
            null,
            201
        );
    }

    public function destroy(Fault $fault, Employee $employee): JsonResponse
    {
        $this->authorize('manageTechnicians', $fault);

        $fault = $this->unassignAction->execute($fault, $employee);

        return ApiResponse::success(
            'Technician unassigned successfully',
            new FaultResource($fault)
        );
    }
}
