<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\Machine\ListMachinesAction;
use App\Actions\V1\Machine\ShowMachineAction;
use App\Http\Requests\Api\V1\ListMachinesRequest;
use App\Http\Resources\V1\MachineResource;
use App\Models\Machine;
use Illuminate\Http\JsonResponse;

class MachineController extends BaseController
{
    public function __construct(
        private ListMachinesAction $listAction,
        private ShowMachineAction  $showAction,
    ) {}

    public function index(ListMachinesRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Machine::class);

        $machines = $this->listAction->execute();

        return $this->successCollection(
            'Machines retrieved successfully',
            MachineResource::collection($machines),
            $machines
        );
    }

    public function show(Machine $machine): JsonResponse
    {
        $this->authorize('view', $machine);

        $machine = $this->showAction->execute($machine);

        return $this->successResource(
            'Machine retrieved successfully',
            new MachineResource($machine)
        );
    }
}
