<?php

namespace App\Policies;

class MachinePolicy extends BasePolicy
{
    protected string $viewAnyPermission = 'view_machines';
    protected string $viewPermission    = 'view_machines';
}
