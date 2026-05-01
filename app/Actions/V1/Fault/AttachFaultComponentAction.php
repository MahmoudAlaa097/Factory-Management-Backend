<?php

namespace App\Actions\V1\Fault;

use App\Http\Requests\Api\V1\StoreFaultComponentRequest;
use App\Models\Fault;
use App\Services\FaultComponentService;

class AttachFaultComponentAction
{
    public function __construct(private FaultComponentService $service) {}

    public function execute(StoreFaultComponentRequest $request, Fault $fault): Fault
    {
        $this->service->store($request, $fault, $request->user());

        return $fault->fresh();
    }
}
