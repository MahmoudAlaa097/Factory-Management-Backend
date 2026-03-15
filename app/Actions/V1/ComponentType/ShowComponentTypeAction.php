<?php

namespace App\Actions\V1\ComponentType;

use App\Actions\V1\Base\BaseShowAction;
use App\Services\ComponentTypeService;

class ShowComponentTypeAction extends BaseShowAction
{
    public function __construct(private ComponentTypeService $service) {}

    protected function service(): ComponentTypeService
    {
        return $this->service;
    }
}
