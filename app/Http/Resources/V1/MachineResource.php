<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'number'                => $this->number,
            'is_active'             => $this->is_active,
            'division'              => new DivisionResource($this->whenLoaded('division')),
            'machine_type'          => new MachineTypeResource($this->whenLoaded('machineType')),
            'faults'                => FaultResource::collection($this->whenLoaded('faults')),
            'component_replacements'=> ComponentReplacementResource::collection($this->whenLoaded('componentReplacements')),
            'created_at'            => $this->created_at,
        ];
    }
}
