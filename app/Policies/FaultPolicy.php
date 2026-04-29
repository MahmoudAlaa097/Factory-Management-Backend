<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Fault;
use App\Enums\EmployeeRole;
use App\Enums\FaultStatus;

class FaultPolicy extends BasePolicy
{
    private function inMaintenance(User $user, Fault $fault): bool
    {
        return $this->isMaintenance($user)
            && $this->sameManagement($user, $fault);
    }

    private function inProduction(User $user, Fault $fault): bool
    {
        return $this->isProduction($user)
            && $this->sameDivision($user, $fault);
    }

    private function isAssigned(User $user, Fault $fault): bool
    {
        return $fault->technicians()
            ->where('employee_id', $user->employee->id)
            ->exists();
    }

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

    private function canEditInProgress(Fault $fault): bool
    {
        return $fault->status->is(FaultStatus::InProgress);
    }

    public function viewAny(User $user): bool
    {
        return $this->allow($user, 'view_faults');
    }

    public function view(User $user, Fault $fault): bool
    {
        if (!$this->allow($user, 'view_faults')) return false;
        if (!$this->hasEmployee($user)) return false;

        $employee = $user->employee;

        if ($this->inMaintenance($user, $fault)) {

            if ($employee->role->is(EmployeeRole::Technician)) {
                return $fault->status->is(FaultStatus::Open)
                    || $fault->status->is(FaultStatus::InProgress)
                    || $this->isAssigned($user, $fault);
            }

            return true;
        }

        if ($this->inProduction($user, $fault)) {

            if ($employee->role->is(EmployeeRole::Operator)) {
                return $fault->reported_by === $employee->id;
            }

            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $this->allow($user, 'report_fault');
    }

    public function respond(User $user, Fault $fault): bool
    {
        return $this->allow($user, 'respond_fault')
            && $this->hasEmployee($user)
            && $this->canRespond($fault)
            && $this->isTechnician($user)
            && $this->inMaintenance($user, $fault);
    }

    public function resolve(User $user, Fault $fault): bool
    {
        return $this->allow($user, 'resolve_fault')
            && $this->hasEmployee($user)
            && $this->canResolve($fault)
            && $this->isTechnician($user)
            && $this->inMaintenance($user, $fault)
            && $this->isAssigned($user, $fault);
    }

    public function accept(User $user, Fault $fault): bool
    {
        return $this->allow($user, 'accept_fault')
            && $this->hasEmployee($user)
            && $this->canAccept($fault)
            && $this->isOperator($user)
            && $this->inProduction($user, $fault)
            && $fault->reported_by === $user->employee->id;
    }

    public function approve(User $user, Fault $fault): bool
    {
        return $this->allow($user, 'approve_fault')
            && $this->hasEmployee($user)
            && $this->canApprove($fault)
            && $this->isSupervisor($user)
            && $this->inMaintenance($user, $fault);
    }

    public function close(User $user, Fault $fault): bool
    {
        return $this->allow($user, 'close_fault')
            && $this->hasEmployee($user)
            && $this->canClose($fault)
            && $this->isEngineer($user)
            && $this->inMaintenance($user, $fault);
    }

    public function assignTechnician(User $user, Fault $fault): bool
    {
        return $this->allow($user, 'manage_technicians')
            && $this->hasEmployee($user)
            && $this->canEditInProgress($fault)
            && $this->inMaintenance($user, $fault)
            && ($this->isSupervisor($user) || $this->isEngineer($user));
    }

    public function unassignTechnician(User $user, Fault $fault): bool
    {
        return $this->assignTechnician($user, $fault);
    }

    public function manageComponents(User $user, Fault $fault): bool
    {
        return $this->allow($user, 'manage_components')
            && $this->hasEmployee($user)
            && $this->canEditInProgress($fault)
            && $this->inMaintenance($user, $fault)
            && (
                $this->isTechnician($user)
                || $this->isSupervisor($user)
                || $this->isEngineer($user)
            );
    }

    public function logReplacement(User $user, Fault $fault): bool
    {
        return $this->allow($user, 'log_replacement')
            && $this->hasEmployee($user)
            && ($this->canEditInProgress($fault) || $this->canResolve($fault))
            && $this->inMaintenance($user, $fault)
            && ($this->isTechnician($user) || $this->isSupervisor($user));
    }

    public function viewReplacements(User $user, Fault $fault): bool
    {
        return $this->allow($user, 'view_faults')
            && $this->hasEmployee($user)
            && $this->inMaintenance($user, $fault);
    }
}
