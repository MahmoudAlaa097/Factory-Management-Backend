<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\MachineType\ListMachineTypesAction;
use App\Actions\V1\MachineType\ShowMachineTypeAction;
use App\Http\Requests\Api\V1\ListMachineTypesRequest;
use App\Http\Resources\V1\MachineTypeResource;
use App\Models\MachineType;
use Illuminate\Http\JsonResponse;

class MachineTypeController extends BaseController
{
    public function __construct(
        private ListMachineTypesAction $listAction,
        private ShowMachineTypeAction  $showAction,
    ) {}

    public function index(ListMachineTypesRequest $request): JsonResponse
    {
        $this->authorize('viewAny', MachineType::class);

        $machineTypes = $this->listAction->execute();

        return $this->successCollection(
            'Machine types retrieved successfully',
            MachineTypeResource::collection($machineTypes),
            $machineTypes
        );
    }

    public function show(MachineType $machineType): JsonResponse
    {
        $this->authorize('view', $machineType);

        $machineType = $this->showAction->execute($machineType);

        return $this->successResource(
            'Machine type retrieved successfully',
            new MachineTypeResource($machineType)
        );
    }
}
