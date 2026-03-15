<?php

namespace App\Services;

use App\Models\MachineType;
use Spatie\QueryBuilder\AllowedFilter;

class MachineTypeService extends BaseService
{
    protected string $model          = MachineType::class;
    protected array $allowedIncludes = [
        'division',
        'sections',
        'machines',
    ];
    protected array $allowedSorts    = [
        'id',
        'name',
        'created_at',
    ];

    protected function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('division_id'),
        ];
    }
}
