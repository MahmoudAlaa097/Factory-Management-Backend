<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\MachineSection\ListMachineSectionsAction;
use App\Actions\V1\MachineSection\ShowMachineSectionAction;
use App\Http\Requests\Api\V1\ListMachineSectionsRequest;
use App\Http\Resources\V1\MachineSectionResource;
use App\Models\MachineSection;
use Illuminate\Http\JsonResponse;

class MachineSectionController extends BaseController
{
    public function __construct(
        private ListMachineSectionsAction $listAction,
        private ShowMachineSectionAction  $showAction,
    ) {}

    public function index(ListMachineSectionsRequest $request): JsonResponse
    {
        $this->authorize('viewAny', MachineSection::class);

        $sections = $this->listAction->execute();

        return $this->successCollection(
            'Machine sections retrieved successfully',
            MachineSectionResource::collection($sections),
            $sections
        );
    }

    public function show(MachineSection $machineSection): JsonResponse
    {
        $this->authorize('view', $machineSection);

        $machineSection = $this->showAction->execute($machineSection);

        return $this->successResource(
            'Machine section retrieved successfully',
            new MachineSectionResource($machineSection)
        );
    }
}
