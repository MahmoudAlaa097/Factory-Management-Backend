<?php

namespace App\Policies;

class MachineTypePolicy extends BasePolicy
{
    protected string $viewAnyPermission = 'view_machine_types';
    protected string $viewPermission    = 'view_machine_types';
}
