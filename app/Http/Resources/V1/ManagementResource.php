<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'type'       => $this->type,
            'divisions'  => DivisionResource::collection($this->whenLoaded('divisions')),
            'employees'  => EmployeeResource::collection($this->whenLoaded('employees')),
            'created_at' => $this->created_at,
        ];
    }
}
