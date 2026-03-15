<?php

namespace App\Services;

use App\Models\MachineComponent;
use Spatie\QueryBuilder\AllowedFilter;

class MachineComponentService extends BaseService
{
    protected string $model          = MachineComponent::class;
    protected array $allowedIncludes = [
        'section',
        'componentType',
    ];
    protected array $allowedSorts    = [
        'id',
        'name',
        'created_at',
    ];

    protected function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('machine_section_id'),
            AllowedFilter::exact('component_type_id'),
        ];
    }
}
