<?php

namespace App\Services;

use App\Models\Employee;
use Spatie\QueryBuilder\AllowedFilter;

class EmployeeService extends BaseService
{
    protected string $model          = Employee::class;
    protected array $allowedIncludes = [
        'user',
        'management',
        'division',
    ];
    protected array $allowedSorts    = [
        'id',
        'name',
        'code',
        'created_at',
    ];

    public function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('management_id'),
            AllowedFilter::exact('division_id'),
            AllowedFilter::exact('role'),
            AllowedFilter::exact('is_active'),
        ];
    }
}
