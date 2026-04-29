<?php

namespace App\Helpers;

use App\Models\Fault;
use App\Models\User;
use App\Scopes\FaultScope;
use Illuminate\Database\Eloquent\Builder;

class QueryScope
{
    public static function faults(User $user): Builder
    {
        return FaultScope::apply(Fault::query(), $user);
    }
}
