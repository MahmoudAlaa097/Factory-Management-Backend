<?php

namespace App\Actions\V1\Base;

abstract class BaseListAction
{
    public function execute(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->service()->list();
    }

    abstract protected function service(): \App\Services\BaseService;
}
