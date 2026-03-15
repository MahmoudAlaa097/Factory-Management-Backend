<?php

namespace App\Actions\V1\Management;

use App\Services\ManagementService;
use App\Actions\V1\Base\BaseShowAction;

class ShowManagementAction extends BaseShowAction
{
    public function __construct(private ManagementService $service) {}

    protected function service(): ManagementService
    {
        return $this->service;
    }
}
