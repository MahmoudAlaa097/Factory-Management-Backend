<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\ComponentType\ListComponentTypesAction;
use App\Actions\V1\ComponentType\ShowComponentTypeAction;
use App\Http\Requests\Api\V1\ListComponentTypesRequest;
use App\Http\Resources\V1\ComponentTypeResource;
use App\Models\ComponentType;
use Illuminate\Http\JsonResponse;

class ComponentTypeController extends BaseController
{
    public function __construct(
        private ListComponentTypesAction $listAction,
        private ShowComponentTypeAction  $showAction,
    ) {}

    public function index(ListComponentTypesRequest $request): JsonResponse
    {
        $this->authorize('viewAny', ComponentType::class);

        $componentTypes = $this->listAction->execute();

        return $this->successCollection(
            'Component types retrieved successfully',
            ComponentTypeResource::collection($componentTypes),
            $componentTypes
        );
    }

    public function show(ComponentType $componentType): JsonResponse
    {
        $this->authorize('view', $componentType);

        $componentType = $this->showAction->execute($componentType);

        return $this->successResource(
            'Component type retrieved successfully',
            new ComponentTypeResource($componentType)
        );
    }
}
