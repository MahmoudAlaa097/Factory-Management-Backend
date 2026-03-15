<?php

namespace App\Actions\V1\Base;

use Illuminate\Database\Eloquent\Model;

abstract class BaseShowAction
{
    public function execute(Model $model): Model
    {
        return $this->service()->show($model);
    }

    abstract protected function service(): \App\Services\BaseService;
}
