<?php

namespace App\Actions\V1\Employee;

use App\Actions\V1\Base\BaseListAction;
use App\Services\EmployeeService;

class ListEmployeesAction extends BaseListAction
{
    public function __construct(private EmployeeService $service) {}

    protected function service(): EmployeeService
    {
        return $this->service;
    }
}
