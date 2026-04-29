<?php

namespace App\Scopes;

use App\Enums\EmployeeRole;
use App\Enums\FaultStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class FaultScope
{
    public static function apply(Builder $query, User $user): Builder
    {
        $employee = $user->employee;

        if (!$employee) {
            return $query->whereRaw('1 = 0');
        }

        if ($employee->role?->isAdmin()) {
            return $query;
        }

        if ($employee->management?->type->isMaintenance()) {
            return static::applyMaintenanceScope($query, $employee);
        }

        if ($employee->management?->type->isProduction()) {
            return static::applyProductionScope($query, $employee);
        }

        return $query->whereRaw('1 = 0');
    }

    /*
    |--------------------------------------------------------------------------
    | 🔧 Maintenance Scope
    |--------------------------------------------------------------------------
    */

    private static function applyMaintenanceScope(Builder $query, $employee): Builder
    {
        $query->where('maintenance_management_id', $employee->management_id);

        if ($employee->role->is(EmployeeRole::Technician)) {
            $query->where(function ($q) use ($employee) {
                $q->whereIn('status', [
                    FaultStatus::Open->value,
                    FaultStatus::InProgress->value,
                ])
                ->orWhereHas('technicians', fn($t) =>
                    $t->where('employee_id', $employee->id)
                );
            });
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | 🏭 Production Scope
    |--------------------------------------------------------------------------
    */

    private static function applyProductionScope(Builder $query, $employee): Builder
    {
        $query->where('division_id', $employee->division_id);

        if ($employee->role->is(EmployeeRole::Operator)) {
            $query->where('reported_by', $employee->id);
        }

        return $query;
    }
}
