<?php

namespace App\Services;

use App\Models\Division;

class DivisionService extends BaseService
{
    protected string $model          = Division::class;
    protected array $allowedIncludes = [
        'management',
        'parent',
        'children',
        'machines',
        'employees',
    ];
    protected array $allowedFilters  = [
        'management_id',
        'parent_division_id',
        'is_active',
    ];
    protected array $allowedSorts    = [
        'id',
        'name',
        'created_at',
    ];
}