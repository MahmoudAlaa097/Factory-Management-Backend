<?php

namespace App\Policies;

use App\Models\User;

abstract class BasePolicy
{
    protected function hasEmployee(User $user): bool
    {
        return $user->employee !== null;
    }

    protected function allow(User $user, string $permission): bool
    {
        return $this->isAdmin($user) || $this->can($user, $permission);
    }

    protected function isAdmin(User $user): bool
    {
        return (bool) $user->employee?->role?->isAdmin();
    }

    protected function isManager(User $user): bool
    {
        return (bool) $user->employee?->role?->isManager();
    }

    protected function isEngineer(User $user): bool
    {
        return (bool) $user->employee?->role?->isEngineer();
    }

    protected function isSupervisor(User $user): bool
    {
        return (bool) $user->employee?->role?->isSupervisor();
    }

    protected function isTechnician(User $user): bool
    {
        return (bool) $user->employee?->role?->isTechnician();
    }

    protected function isOperator(User $user): bool
    {
        return (bool) $user->employee?->role?->isOperator();
    }

    protected function sameManagement(User $user, $model): bool
    {
        return $user->employee?->management_id === $model->maintenance_management_id
            && $user->employee?->management?->type->isMaintenance();
    }

    protected function sameDivision(User $user, $model): bool
    {
        return $user->employee?->division_id === $model->division_id;
    }

    protected function can(User $user, string $permission): bool
    {
        return $user->hasPermissionTo($permission);
    }

    protected function isMaintenance(User $user): bool
    {
        return (bool) $user->employee?->management?->type->isMaintenance();
    }

    protected function isProduction(User $user): bool
    {
        return (bool) $user->employee?->management?->type->isProduction();
    }
}
