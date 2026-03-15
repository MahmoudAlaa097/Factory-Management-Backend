<?php

namespace App\Services;

use App\Models\ComponentType;
use Spatie\QueryBuilder\AllowedFilter;

class ComponentTypeService extends BaseService
{
    protected string $model          = ComponentType::class;
    protected array $allowedIncludes = [
        'machineComponents',
    ];
    protected array $allowedSorts    = [
        'id',
        'name',
        'created_at',
    ];

    protected function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('category'),
        ];
    }
}
