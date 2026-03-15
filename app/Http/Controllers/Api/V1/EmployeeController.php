<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\V1\Employee\ListEmployeesAction;
use App\Actions\V1\Employee\ShowEmployeeAction;
use App\Http\Requests\Api\V1\ListEmployeesRequest;
use App\Http\Resources\V1\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;

class EmployeeController extends BaseController
{
    public function __construct(
        private ListEmployeesAction $listAction,
        private ShowEmployeeAction  $showAction,
    ) {}

    public function index(ListEmployeesRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Employee::class);

        $employees = $this->listAction->execute();

        return $this->successCollection(
            'Employees retrieved successfully',
            EmployeeResource::collection($employees),
            $employees
        );
    }

    public function show(Employee $employee): JsonResponse
    {
        $this->authorize('view', $employee);

        $employee = $this->showAction->execute($employee);

        return $this->successResource(
            'Employee retrieved successfully',
            new EmployeeResource($employee)
        );
    }
}
