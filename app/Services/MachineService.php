<?php

namespace App\Services;

use App\Models\Machine;
use Spatie\QueryBuilder\AllowedFilter;

class MachineService extends BaseService
{
    protected string $model          = Machine::class;
    protected array $allowedIncludes = [
        'division',
        'machineType',
        'faults',
        'componentReplacements',
    ];
    protected array $allowedSorts    = [
        'id',
        'number',
        'created_at',
    ];

    protected function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('division_id'),
            AllowedFilter::exact('machine_type_id'),
            AllowedFilter::exact('is_active'),
        ];
    }
}
