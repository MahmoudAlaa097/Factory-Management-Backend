<?php

namespace App\Services;

use App\Models\MachineSection;

class MachineSectionService extends BaseService
{
    protected string $model          = MachineSection::class;
    protected array $allowedIncludes = [
        'machineTypes',
        'components',
    ];
    protected array $allowedSorts    = [
        'id',
        'name',
        'created_at',
    ];
}
