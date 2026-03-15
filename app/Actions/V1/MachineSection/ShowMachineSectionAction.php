<?php

namespace App\Actions\V1\MachineSection;

use App\Actions\V1\Base\BaseShowAction;
use App\Services\MachineSectionService;

class ShowMachineSectionAction extends BaseShowAction
{
    public function __construct(private MachineSectionService $service) {}

    protected function service(): MachineSectionService
    {
        return $this->service;
    }
}
