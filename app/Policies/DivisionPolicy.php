<?php

namespace App\Policies;

class DivisionPolicy extends BasePolicy
{
    protected string $viewAnyPermission = 'view_divisions';
    protected string $viewPermission    = 'view_divisions';
}
