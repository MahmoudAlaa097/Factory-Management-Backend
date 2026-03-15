<?php

namespace App\Actions\V1\Employee;

use App\Actions\V1\Base\BaseShowAction;
use App\Services\EmployeeService;

class ShowEmployeeAction extends BaseShowAction
{
    public function __construct(private EmployeeService $service) {}

    protected function service(): EmployeeService
    {
        return $this->service;
    }
}
