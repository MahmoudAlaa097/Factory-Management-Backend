<?php

namespace App\Actions\V1\Machine;

use App\Actions\V1\Base\BaseListAction;
use App\Services\MachineService;

class ListMachinesAction extends BaseListAction
{
    public function __construct(private MachineService $service) {}

    protected function service(): MachineService
    {
        return $this->service;
    }
}
