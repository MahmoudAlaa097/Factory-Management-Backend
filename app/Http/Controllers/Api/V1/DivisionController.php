<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\Division\ListDivisionsAction;
use App\Actions\V1\Division\ShowDivisionAction;
use App\Http\Requests\Api\V1\ListDivisionsRequest;
use App\Http\Resources\V1\DivisionResource;
use App\Models\Division;
use Illuminate\Http\JsonResponse;

class DivisionController extends BaseController
{
    public function __construct(
        private ListDivisionsAction $listAction,
        private ShowDivisionAction  $showAction,
    ) {}

    public function index(ListDivisionsRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Division::class);

        $divisions = $this->listAction->execute();

        return $this->successCollection(
            'Divisions retrieved successfully',
            DivisionResource::collection($divisions),
            $divisions
        );
    }

    public function show(Division $division): JsonResponse
    {
        $this->authorize('view', $division);

        $division = $this->showAction->execute($division);

        return $this->successResource(
            'Division retrieved successfully',
            new DivisionResource($division)
        );
    }
}
