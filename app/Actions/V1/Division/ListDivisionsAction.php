<?php

namespace App\Actions\V1\Division;

use App\Actions\V1\Base\BaseListAction;
use App\Services\DivisionService;

class ListDivisionsAction extends BaseListAction
{
    public function __construct(private DivisionService $service) {}

    protected function service(): DivisionService
    {
        return $this->service;
    }
}
