<?php

namespace App\Actions\V1\MachineSection;

use App\Actions\V1\Base\BaseListAction;
use App\Services\MachineSectionService;

class ListMachineSectionsAction extends BaseListAction
{
    public function __construct(private MachineSectionService $service) {}

    protected function service(): MachineSectionService
    {
        return $this->service;
    }
}
