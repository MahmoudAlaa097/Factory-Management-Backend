<?php

namespace App\Support;

use App\Models\User;
use App\Models\Fault;

class UserContext
{
    // -----------------------------------------------------------------------
    // Role checks
    // -----------------------------------------------------------------------

    public static function isAdmin(User $user): bool
    {
        return (bool) $user->employee?->isAdmin();
    }

    public static function isManager(User $user): bool
    {
        return (bool) $user->employee?->isManager();
    }

    public static function isEngineer(User $user): bool
    {
        return (bool) $user->employee?->isEngineer();
    }

    public static function isSupervisor(User $user): bool
    {
        return (bool) $user->employee?->isSupervisor();
    }

    public static function isTechnician(User $user): bool
    {
        return (bool) $user->employee?->isTechnician();
    }

    public static function isOperator(User $user): bool
    {
        return (bool) $user->employee?->isOperator();
    }

    // -----------------------------------------------------------------------
    // Management-type checks
    // -----------------------------------------------------------------------

    public static function isMaintenance(User $user): bool
    {
        return (bool) $user->employee?->isMaintenance();
    }

    public static function isProduction(User $user): bool
    {
        return (bool) $user->employee?->isProduction();
    }

    // -----------------------------------------------------------------------
    // Combined role + management-type checks
    // -----------------------------------------------------------------------

    public static function isMaintenanceTechnician(User $user): bool
    {
        return (bool) $user->employee?->isMaintenanceTechnician();
    }

    public static function isMaintenanceSupervisor(User $user): bool
    {
        return (bool) $user->employee?->isMaintenanceSupervisor();
    }

    public static function isMaintenanceEngineer(User $user): bool
    {
        return (bool) $user->employee?->isMaintenanceEngineer();
    }

    public static function isMaintenanceManager(User $user): bool
    {
        return (bool) $user->employee?->isMaintenanceManager();
    }

    public static function isProductionOperator(User $user): bool
    {
        return (bool) $user->employee?->isProductionOperator();
    }

    public static function isProductionSupervisor(User $user): bool
    {
        return (bool) $user->employee?->isProductionSupervisor();
    }

    public static function isProductionEngineer(User $user): bool
    {
        return (bool) $user->employee?->isProductionEngineer();
    }

    public static function isProductionManager(User $user): bool
    {
        return (bool) $user->employee?->isProductionManager();
    }

    // -----------------------------------------------------------------------
    // Scope helpers — generic models
    // -----------------------------------------------------------------------

    public static function sameManagement(User $user, $model): bool
    {
        return $user->employee?->management_id === $model->management_id;
    }

    public static function sameDivision(User $user, $model): bool
    {
        return $user->employee?->division_id === $model->division_id;
    }

    // -----------------------------------------------------------------------
    // Scope helpers — Fault-specific
    // -----------------------------------------------------------------------

    public static function ownsFaultManagement(User $user, Fault $fault): bool
    {
        return static::isMaintenance($user)
            && $user->employee?->management_id === $fault->maintenance_management_id;
    }

    public static function ownsFaultDivision(User $user, Fault $fault): bool
    {
        return $user->employee?->division_id === $fault->division_id;
    }
}
