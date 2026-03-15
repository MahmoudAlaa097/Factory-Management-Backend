<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineComponentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'section'        => new MachineSectionResource($this->whenLoaded('section')),
            'component_type' => new ComponentTypeResource($this->whenLoaded('componentType')),
            'created_at'     => $this->created_at,
        ];
    }
}
