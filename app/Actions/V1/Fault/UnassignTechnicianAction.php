<?php

namespace App\Actions\V1\Fault;

use App\Models\Employee;
use App\Models\Fault;
use App\Services\FaultService;

class UnassignTechnicianAction
{
    public function __construct(private FaultService $service) {}

    public function execute(Fault $fault, Employee $employee): Fault
    {
        return $this->service->unassignTechnician($fault, $employee);
    }
}
