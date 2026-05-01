<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComponentReplacementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'is_new'      => $this->is_new,
            'fault'       => new FaultResource($this->whenLoaded('fault')),
            'machine'     => new MachineResource($this->whenLoaded('machine')),
            'component'   => new MachineComponentResource($this->whenLoaded('component')),
            'replaced_by' => new EmployeeResource($this->whenLoaded('replacedBy')),
            'replaced_at' => $this->replaced_at,
            'created_at'  => $this->created_at,
        ];
    }
}
