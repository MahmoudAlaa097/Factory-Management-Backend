<?php

namespace App\Actions\V1\Fault;

use App\Http\Requests\Api\V1\StoreFaultTechnicianRequest;
use App\Models\Fault;
use App\Services\FaultService;

class AssignTechnicianAction
{
    public function __construct(private FaultService $service) {}

    public function execute(StoreFaultTechnicianRequest $request, Fault $fault): Fault
    {
        return $this->service->assignTechnician($fault, $request->technician_id);
    }
}
