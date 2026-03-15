<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DivisionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'is_active'  => $this->is_active,
            'management' => new ManagementResource($this->whenLoaded('management')),
            'parent'     => new DivisionResource($this->whenLoaded('parent')),
            'children'   => DivisionResource::collection($this->whenLoaded('children')),
            'machines'   => MachineResource::collection($this->whenLoaded('machines')),
            'employees'  => EmployeeResource::collection($this->whenLoaded('employees')),
            'created_at' => $this->created_at,
        ];
    }
}
