<?php

namespace App\Actions\V1\ComponentType;

use App\Actions\V1\Base\BaseListAction;
use App\Services\ComponentTypeService;

class ListComponentTypesAction extends BaseListAction
{
    public function __construct(private ComponentTypeService $service) {}

    protected function service(): ComponentTypeService
    {
        return $this->service;
    }
}
