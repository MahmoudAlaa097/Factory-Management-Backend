<?php

namespace App\Actions\V1\Division;

use App\Actions\V1\Base\BaseShowAction;
use App\Services\DivisionService;

class ShowDivisionAction extends BaseShowAction
{
    public function __construct(private DivisionService $service) {}

    protected function service(): DivisionService
    {
        return $this->service;
    }
}
