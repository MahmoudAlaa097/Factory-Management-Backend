<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'model'          => $this->model,
            'manufacturer'   => $this->manufacturer,
            'specifications' => $this->specifications,
            'manual_url'     => $this->manual_url,
            'image_url'      => $this->image_url,
            'division'       => new DivisionResource($this->whenLoaded('division')),
            'sections'       => MachineSectionResource::collection($this->whenLoaded('sections')),
            'machines'       => MachineResource::collection($this->whenLoaded('machines')),
            'created_at'     => $this->created_at,
        ];
    }
}
