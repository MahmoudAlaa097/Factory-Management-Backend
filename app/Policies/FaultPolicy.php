<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Fault;
use App\Enums\FaultStatus;
use App\Support\UserContext;

class FaultPolicy extends BasePolicy
{
    // -----------------------------------------------------------------------
    // Status machine helpers
    // -----------------------------------------------------------------------

    private function canRespond(Fault $fault): bool
    {
        return $fault->status->is(FaultStatus::Open);
    }

    private function canResolve(Fault $fault): bool
    {
        return $fault->status->is(FaultStatus::InProgress);
    }

    private function canAccept(Fault $fault): bool
    {
        return $fault->status->is(FaultStatus::Resolved);
    }

    private function canApprove(Fault $fault): bool
    {
        return $fault->status->is(FaultStatus::OperatorAccepted);
    }

    private function canClose(Fault $fault): bool
    {
        return $fault->status->is(FaultStatus::MaintenanceApproved);
    }


    private function isAssigned(User $user, Fault $fault): bool
    {
        return $fault->technicians()
            ->where('employees.id', $user->employee->id)
            ->exists();
    }

    // -----------------------------------------------------------------------
    // Read
    // -----------------------------------------------------------------------

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user)
            || $this->isMaintenance($user)
            || $this->isProduction($user);
    }

    public function view(User $user, Fault $fault): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        // Maintenance — scoped to their management
        if (UserContext::ownsFaultManagement($user, $fault)) {
            // Technician: open/in_progress faults + their assigned faults
            if ($this->isMaintenanceTechnician($user)) {
                return $fault->status->is(FaultStatus::Open)
                    || $fault->status->is(FaultStatus::InProgress)
                    || $this->isAssigned($user, $fault);
            }

            // Supervisor / Engineer / Manager: all faults in their management
            return true;
        }

        // Production — scoped to their division
        if (UserContext::ownsFaultDivision($user, $fault)) {
            // Operator: own faults only
            if ($this->isProductionOperator($user)) {
                return $fault->reported_by === $user->employee->id;
            }

            // Supervisor / Engineer / Manager: all faults in their division
            return true;
        }

        return false;
    }

    // -----------------------------------------------------------------------
    // Fault lifecycle
    // -----------------------------------------------------------------------

    public function create(User $user): bool
    {
        return $this->isProductionOperator($user)
            || ($this->isEngineer($user) && $this->isProduction($user));
    }

    public function respond(User $user, Fault $fault): bool
    {
        return $this->canRespond($fault)
            && UserContext::ownsFaultManagement($user, $fault)
            && (
                $this->isMaintenanceTechnician($user)
                || $this->isMaintenanceSupervisor($user)
            );
    }

    public function resolve(User $user, Fault $fault): bool
    {
        if (! $this->canResolve($fault)) {
            return false;
        }

        if (! UserContext::ownsFaultManagement($user, $fault)) {
            return false;
        }

        // Technician must be assigned
        if ($this->isMaintenanceTechnician($user)) {
            return $this->isAssigned($user, $fault);
        }

        // Supervisor can resolve without being assigned
        if ($this->isMaintenanceSupervisor($user)) {
            return true;
        }

        return false;
    }

    public function accept(User $user, Fault $fault): bool
    {
        return $this->canAccept($fault)
            && UserContext::ownsFaultDivision($user, $fault)
            && (
                $this->isProductionOperator($user)
                || $this->isProductionSupervisor($user)
                || ($this->isEngineer($user) && $this->isProduction($user))
            );
    }

    public function approve(User $user, Fault $fault): bool
    {
        return $this->canApprove($fault)
            && UserContext::ownsFaultManagement($user, $fault)
            && $this->isMaintenanceSupervisor($user);
    }

    public function close(User $user, Fault $fault): bool
    {
        return $this->canClose($fault)
            && UserContext::ownsFaultManagement($user, $fault)
            && $this->isEngineer($user)
            && $this->isMaintenance($user);
    }

    // -----------------------------------------------------------------------
    // Technician assignment
    // -----------------------------------------------------------------------

    public function assignTechnician(User $user, Fault $fault): bool
    {
        return $this->canResolve($fault)
            && UserContext::ownsFaultManagement($user, $fault)
            && (
                $this->isMaintenanceSupervisor($user)
                || ($this->isEngineer($user) && $this->isMaintenance($user))
            );
    }

    public function unassignTechnician(User $user, Fault $fault): bool
    {
        return $this->assignTechnician($user, $fault);
    }

    // -----------------------------------------------------------------------
    // Components
    // -----------------------------------------------------------------------

    public function manageComponents(User $user, Fault $fault): bool
    {
        return $this->canResolve($fault)
            && UserContext::ownsFaultManagement($user, $fault)
            && (
                $this->isMaintenanceTechnician($user)
                || $this->isMaintenanceSupervisor($user)
                || ($this->isEngineer($user) && $this->isMaintenance($user))
            );
    }

    // -----------------------------------------------------------------------
    // Replacements
    // -----------------------------------------------------------------------

    public function logReplacement(User $user, Fault $fault): bool
    {
        return $this->canResolve($fault)
            && UserContext::ownsFaultManagement($user, $fault)
            && (
                $this->isMaintenanceTechnician($user)
                || $this->isMaintenanceSupervisor($user)
            );
    }

    public function updateResolution(User $user, Fault $fault): bool
    {
        if (! UserContext::ownsFaultManagement($user, $fault)) {
            return false;
        }

        // Closed — engineer and manager only
        if ($fault->status->is(FaultStatus::Closed)) {
            return ($this->isEngineer($user) || $this->isManager($user))
                && $this->isMaintenance($user);
        }

        // in_progress through maintenance_approved — supervisor, engineer, manager
        return $this->isMaintenanceSupervisor($user)
            || ($this->isEngineer($user) && $this->isMaintenance($user))
            || ($this->isManager($user) && $this->isMaintenance($user));
    }

    public function viewReplacements(User $user, Fault $fault): bool
    {
        return $this->isAdmin($user)
            || (
                $this->isMaintenance($user)
                && UserContext::ownsFaultManagement($user, $fault)
            );
    }
}
