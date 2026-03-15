<?php

namespace App\Policies;

use App\Models\User;

abstract class BasePolicy
{
    protected string $viewAnyPermission;
    protected string $viewPermission;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo($this->viewAnyPermission);
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo($this->viewPermission);
    }
}
