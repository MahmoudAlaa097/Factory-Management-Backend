<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Machine;

class MachinePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->allow($user, 'view_machines');
    }

    public function view(User $user, Machine $machine): bool
    {
        return $this->allow($user, 'view_machines')
            && $this->hasEmployee($user)
            && (
                $this->inMaintenance($user, $machine)
                || $this->inProduction($user, $machine)
            );
    }

    public function create(User $user): bool
    {
        return $this->allow($user, 'create_machines');
    }

    public function update(User $user, Machine $machine): bool
    {
        return $this->allow($user, 'update_machines')
            && $this->hasEmployee($user)
            && (
                $this->inMaintenance($user, $machine)
                || $this->inProduction($user, $machine)
            );
    }

    public function delete(User $user, Machine $machine): bool
    {
        return $this->allow($user, 'delete_machines')
            && $this->hasEmployee($user)
            && $this->inProduction($user, $machine);
    }

    private function inMaintenance(User $user, Machine $machine): bool
    {
        return $this->isMaintenance($user)
            && $user->employee?->management_id === $machine->maintenance_management_id;
    }

    private function inProduction(User $user, Machine $machine): bool
    {
        return $this->isProduction($user)
            && $this->sameDivision($user, $machine);
    }
}
