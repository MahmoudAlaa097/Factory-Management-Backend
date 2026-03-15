<?php

namespace App\Policies;

class ManagementPolicy extends BasePolicy
{
    protected string $viewAnyPermission = 'view_managements';
    protected string $viewPermission    = 'view_managements';
}
