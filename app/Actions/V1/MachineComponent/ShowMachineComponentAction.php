<?php

namespace App\Actions\V1\MachineComponent;

use App\Actions\V1\Base\BaseShowAction;
use App\Services\MachineComponentService;

class ShowMachineComponentAction extends BaseShowAction
{
    public function __construct(private MachineComponentService $service) {}

    protected function service(): MachineComponentService
    {
        return $this->service;
    }
}
