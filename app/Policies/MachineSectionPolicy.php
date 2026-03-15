<?php

namespace App\Policies;

class MachineSectionPolicy extends BasePolicy
{
    protected string $viewAnyPermission = 'view_machine_sections';
    protected string $viewPermission    = 'view_machine_sections';
}
