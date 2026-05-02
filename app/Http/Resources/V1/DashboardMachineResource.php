<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardMachineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'number'                  => $this->number,
            'division'                => $this->division?->name,
            'active_faults'           => $this->active_faults,
            'total_faults_this_period'=> $this->total_faults_this_period,
            'last_fault_at'           => $this->last_fault_at,
            'avg_resolution_time'     => $this->avg_resolution_time,
            'status'                  => $this->status,
        ];
    }
}
