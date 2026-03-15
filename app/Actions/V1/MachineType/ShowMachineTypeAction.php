<?php

namespace App\Actions\V1\MachineType;

use App\Actions\V1\Base\BaseShowAction;
use App\Services\MachineTypeService;

class ShowMachineTypeAction extends BaseShowAction
{
    public function __construct(private MachineTypeService $service) {}

    protected function service(): MachineTypeService
    {
        return $this->service;
    }
}
