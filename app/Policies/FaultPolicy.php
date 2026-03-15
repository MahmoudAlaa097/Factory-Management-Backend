<?php

namespace App\Policies;

use App\Enums\EmployeeRole;
use App\Enums\FaultStatus;
use App\Models\Fault;
use App\Models\User;

class FaultPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_faults');
    }

    public function view(User $user, Fault $fault): bool
    {
        if (!$user->hasPermissionTo('view_faults')) return false;

        $employee = $user->employee;

        // Admin sees all
        if ($employee->role->isAdmin()) return true;

        // Maintenance employees — scoped to their management
        if ($employee->management->type->isMaintenance()) {

            // Wrong maintenance management
            if ($employee->management_id !== $fault->maintenance_management_id) return false;

            // Technician — open/in_progress or own assigned faults
            if ($employee->role->is(EmployeeRole::Technician)) {
                if ($fault->status->is(FaultStatus::Open) ||
                    $fault->status->is(FaultStatus::InProgress)) return true;

                return $fault->technicians->contains('id', $employee->id);
            }

            // Supervisor and Engineer — all faults directed to their management
            return true;
        }

        // Production employees — scoped to their division
        if ($employee->management->type->isProduction()) {

            // Wrong division
            if ($employee->division_id !== $fault->division_id) return false;

            // Operator — own faults only
            if ($employee->role->is(EmployeeRole::Operator)) {
                return $fault->reported_by === $employee->id;
            }

            // Production Supervisor — all division faults
            return true;
        }

        return false;
    }

    public function store(User $user): bool
    {
        if (!$user->hasPermissionTo('report_fault')) return false;

        $employee = $user->employee;

        if ($employee->role->isAdmin()) return true;

        // Only production operators and supervisors can report
        return $employee->management->type->isProduction() && (
            $employee->role->is(EmployeeRole::Operator) ||
            $employee->role->is(EmployeeRole::Supervisor)
        );
    }

    public function respond(User $user, Fault $fault): bool
    {
        if (!$user->hasPermissionTo('respond_fault')) return false;
        if (!$fault->status->is(FaultStatus::Open)) return false;

        $employee = $user->employee;

        if ($employee->role->isAdmin()) return true;

        // Must be maintenance technician from correct management
        return $employee->role->is(EmployeeRole::Technician)
            && $employee->management->type->isMaintenance()
            && $employee->management_id === $fault->maintenance_management_id;
    }

    public function resolve(User $user, Fault $fault): bool
    {
        if (!$user->hasPermissionTo('resolve_fault')) return false;
        if (!$fault->status->is(FaultStatus::InProgress)) return false;

        $employee = $user->employee;

        if ($employee->role->isAdmin()) return true;

        // Must be assigned technician from correct management
        return $employee->role->is(EmployeeRole::Technician)
            && $employee->management->type->isMaintenance()
            && $employee->management_id === $fault->maintenance_management_id
            && $fault->technicians->contains('id', $employee->id);
    }

    public function accept(User $user, Fault $fault): bool
    {
        if (!$user->hasPermissionTo('accept_fault')) return false;
        if (!$fault->status->is(FaultStatus::Resolved)) return false;

        $employee = $user->employee;

        if ($employee->role->isAdmin()) return true;

        // Only original reporter can accept
        return $employee->role->is(EmployeeRole::Operator)
            && $employee->management->type->isProduction()
            && $fault->reported_by === $employee->id;
    }

    public function approve(User $user, Fault $fault): bool
    {
        if (!$user->hasPermissionTo('approve_fault')) return false;
        if (!$fault->status->is(FaultStatus::OperatorAccepted)) return false;

        $employee = $user->employee;

        if ($employee->role->isAdmin()) return true;

        // Maintenance supervisor from correct management
        return $employee->role->is(EmployeeRole::Supervisor)
            && $employee->management->type->isMaintenance()
            && $employee->management_id === $fault->maintenance_management_id;
    }

    public function close(User $user, Fault $fault): bool
    {
        if (!$user->hasPermissionTo('close_fault')) return false;
        if (!$fault->status->is(FaultStatus::MaintenanceApproved)) return false;

        $employee = $user->employee;

        if ($employee->role->isAdmin()) return true;

        // Maintenance engineer from correct management
        return $employee->role->is(EmployeeRole::Engineer)
            && $employee->management->type->isMaintenance()
            && $employee->management_id === $fault->maintenance_management_id;
    }
}
