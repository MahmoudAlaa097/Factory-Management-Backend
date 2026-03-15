<?php

namespace App\Services;

use App\Models\Management;
use App\Services\BaseService;

class ManagementService extends BaseService
{
    protected string $model          = Management::class;
    protected array $allowedIncludes = ['divisions', 'employees'];
}
