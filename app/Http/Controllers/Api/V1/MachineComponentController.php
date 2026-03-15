<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\MachineComponent\ListMachineComponentsAction;
use App\Actions\V1\MachineComponent\ShowMachineComponentAction;
use App\Http\Requests\Api\V1\ListMachineComponentsRequest;
use App\Http\Resources\V1\MachineComponentResource;
use App\Models\MachineComponent;
use Illuminate\Http\JsonResponse;

class MachineComponentController extends BaseController
{
    public function __construct(
        private ListMachineComponentsAction $listAction,
        private ShowMachineComponentAction  $showAction,
    ) {}

    public function index(ListMachineComponentsRequest $request): JsonResponse
    {
        $this->authorize('viewAny', MachineComponent::class);

        $components = $this->listAction->execute();

        return $this->successCollection(
            'Machine components retrieved successfully',
            MachineComponentResource::collection($components),
            $components
        );
    }

    public function show(MachineComponent $machineComponent): JsonResponse
    {
        $this->authorize('view', $machineComponent);

        $machineComponent = $this->showAction->execute($machineComponent);

        return $this->successResource(
            'Machine component retrieved successfully',
            new MachineComponentResource($machineComponent)
        );
    }
}
