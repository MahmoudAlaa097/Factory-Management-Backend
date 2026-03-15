<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\ManagementResource;
use App\Models\Management;
use Illuminate\Http\JsonResponse;
use App\Actions\V1\Management\ListManagementsAction;
use App\Actions\V1\Management\ShowManagementAction;
use App\Http\Requests\Api\V1\ListManagementsRequest;

// ManagementController
class ManagementController extends BaseController
{
    public function __construct(
        private ListManagementsAction $listAction,
        private ShowManagementAction  $showAction,
    ) {}

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Management::class);

        $managements = $this->listAction->execute();

        return $this->successCollection(
            'Managements retrieved successfully',
            ManagementResource::collection($managements),
            $managements
        );
    }

    public function show(Management $management): JsonResponse
    {
        $this->authorize('view', $management);

        $management = $this->showAction->execute($management);

        return $this->successResource(
            'Management retrieved successfully',
            new ManagementResource($management)
        );
    }
}
