<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employee;

class EmployeePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->hasEmployee($user);
    }

    public function view(User $user, Employee $employee): bool
    {
        return $this->allow($user, 'view_employees')
            && $this->sameManagement($user, $employee);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user) || $this->isManager($user);
    }

    public function update(User $user, Employee $employee): bool
    {
        return $this->isAdmin($user)
            || ($this->isManager($user) && $this->sameManagement($user, $employee));
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $this->isAdmin($user);
    }
}
