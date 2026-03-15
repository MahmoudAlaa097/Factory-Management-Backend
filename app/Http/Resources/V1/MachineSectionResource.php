<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineSectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'machine_types' => MachineTypeResource::collection($this->whenLoaded('machineTypes')),
            'components'   => MachineComponentResource::collection($this->whenLoaded('components')),
            'created_at'   => $this->created_at,
        ];
    }
}
