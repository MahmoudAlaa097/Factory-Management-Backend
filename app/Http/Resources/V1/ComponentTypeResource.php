<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComponentTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'category'          => $this->category,
            'machine_components' => MachineComponentResource::collection($this->whenLoaded('machineComponents')),
            'created_at'        => $this->created_at,
        ];
    }
}
