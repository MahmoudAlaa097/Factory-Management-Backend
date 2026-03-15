<?php

namespace App\Actions\V1\Management;

use App\Services\ManagementService;
use App\Actions\V1\Base\BaseListAction;

class ListManagementsAction extends BaseListAction
{
    public function __construct(private ManagementService $service) {}

    protected function service(): ManagementService
    {
        return $this->service;
    }
}
