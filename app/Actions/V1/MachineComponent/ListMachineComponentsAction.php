<?php

namespace App\Actions\V1\MachineComponent;

use App\Actions\V1\Base\BaseListAction;
use App\Services\MachineComponentService;

class ListMachineComponentsAction extends BaseListAction
{
    public function __construct(private MachineComponentService $service) {}

    protected function service(): MachineComponentService
    {
        return $this->service;
    }
}
