<?php

namespace App\Actions\V1\MachineType;

use App\Actions\V1\Base\BaseListAction;
use App\Services\MachineTypeService;

class ListMachineTypesAction extends BaseListAction
{
    public function __construct(private MachineTypeService $service) {}

    protected function service(): MachineTypeService
    {
        return $this->service;
    }
}
