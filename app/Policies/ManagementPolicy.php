<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Management;

class ManagementPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(User $user, Management $management): bool
    {
        return $this->isAdmin($user);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, Management $management): bool
    {
        return $this->isAdmin($user);
    }

    public function delete(User $user, Management $management): bool
    {
        return $this->isAdmin($user);
    }
}
