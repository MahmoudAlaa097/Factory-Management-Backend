<?php

namespace App\Services;

use App\Models\ComponentReplacement;
use App\Models\Fault;
use App\Models\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ComponentReplacementService extends BaseService
{
    protected string $model          = ComponentReplacement::class;
    protected array $allowedIncludes = [
        'fault',
        'machine',
        'component',
        'replacedBy',
    ];
    protected array $allowedSorts    = [
        'id',
        'replaced_at',
        'created_at',
    ];

    protected function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('fault_id'),
            AllowedFilter::exact('machine_id'),
            AllowedFilter::exact('machine_component_id'),
            AllowedFilter::exact('is_new'),
        ];
    }

    public function listForFault(Fault $fault): LengthAwarePaginator
    {
        return QueryBuilder::for(
                ComponentReplacement::where('fault_id', $fault->id)
            )
            ->allowedIncludes($this->allowedIncludes)
            ->allowedFilters($this->getAllowedFilters())
            ->allowedSorts($this->allowedSorts)
            ->paginate(request('per_page', 15));
    }

    public function show(\Illuminate\Database\Eloquent\Model $model): \Illuminate\Database\Eloquent\Model
    {
        return QueryBuilder::for(ComponentReplacement::class)
            ->allowedIncludes($this->allowedIncludes)
            ->findOrFail($model->id);
    }
}
