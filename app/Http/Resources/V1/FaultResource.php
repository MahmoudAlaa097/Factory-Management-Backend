<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                       => $this->id,
            'status'                   => $this->status,
            'description'              => $this->description,
            'machine'                  => new MachineResource($this->whenLoaded('machine')),
            'division'                 => new DivisionResource($this->whenLoaded('division')),
            'maintenance_management'   => new ManagementResource($this->whenLoaded('maintenanceManagement')),
            'reporter'                 => new EmployeeResource($this->whenLoaded('reporter')),
            'maintenance_approver'     => new EmployeeResource($this->whenLoaded('maintenanceApprover')),
            'closer'                   => new EmployeeResource($this->whenLoaded('closer')),
            'technicians'              => EmployeeResource::collection($this->whenLoaded('technicians')),
            'components'               => FaultComponentResource::collection($this->whenLoaded('components')),
            'replacements'             => ComponentReplacementResource::collection($this->whenLoaded('replacements')),
            'reported_at'              => $this->reported_at,
            'technician_started_at'    => $this->technician_started_at,
            'resolved_at'              => $this->resolved_at,
            'operator_accepted_at'     => $this->operator_accepted_at,
            'maintenance_approved_at'  => $this->maintenance_approved_at,
            'closed_at'                => $this->closed_at,
            'time_consumed'            => $this->time_consumed,
            'created_at'               => $this->created_at,
        ];
    }
}
