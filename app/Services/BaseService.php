<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

abstract class BaseService
{
    protected string $model;
    protected array $allowedIncludes = [];
    protected array $allowedFilters  = [];
    protected array $allowedSorts    = [];

    public function list(): LengthAwarePaginator
    {
        return QueryBuilder::for($this->model)
            ->allowedIncludes($this->allowedIncludes)
            ->allowedFilters($this->getAllowedFilters())
            ->allowedSorts($this->allowedSorts)
            ->paginate(request('per_page', 15));
    }

    public function show(Model $model): Model
    {
        return QueryBuilder::for($this->model)
            ->allowedIncludes($this->allowedIncludes)
            ->findOrFail($model->id);
    }

    protected function getAllowedFilters(): array
    {
        return array_map(
            fn($filter) => AllowedFilter::exact($filter),
            $this->allowedFilters
        );
    }
}
