<?php

namespace App\Policies;

class EmployeePolicy extends BasePolicy
{
    protected string $viewAnyPermission = 'view_employees';
    protected string $viewPermission    = 'view_employees';
}
