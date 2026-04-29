<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Division;

class DivisionPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->hasEmployee($user);
    }

    public function view(User $user, Division $division): bool
    {
        return $this->allow($user, 'view_divisions')
            && $this->sameManagement($user, $division);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user) || $this->isManager($user);
    }

    public function update(User $user, Division $division): bool
    {
        return $this->isAdmin($user)
            || ($this->isManager($user) && $this->sameManagement($user, $division));
    }

    public function delete(User $user, Division $division): bool
    {
        return $this->isAdmin($user);
    }
}
