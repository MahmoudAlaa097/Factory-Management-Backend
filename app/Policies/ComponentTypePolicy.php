<?php

namespace App\Policies;

class ComponentTypePolicy extends BasePolicy
{
    protected string $viewAnyPermission = 'view_component_types';
    protected string $viewPermission    = 'view_component_types';
}
