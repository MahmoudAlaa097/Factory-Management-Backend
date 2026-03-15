<?php

namespace App\Actions\V1\Machine;

use App\Actions\V1\Base\BaseShowAction;
use App\Services\MachineService;

class ShowMachineAction extends BaseShowAction
{
    public function __construct(private MachineService $service) {}

    protected function service(): MachineService
    {
        return $this->service;
    }
}
