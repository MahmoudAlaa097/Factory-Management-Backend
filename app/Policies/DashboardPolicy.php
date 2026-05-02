<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Dashboard;

class DashboardPolicy extends BasePolicy
{
    public function view(User $user): bool
    {
        return true;
    }
}
