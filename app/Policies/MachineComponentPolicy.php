<?php

namespace App\Policies;

class MachineComponentPolicy extends BasePolicy
{
    protected string $viewAnyPermission = 'view_machine_components';
    protected string $viewPermission    = 'view_machine_components';
}
