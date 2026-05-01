<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaultComponentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'action'    => $this->action->value,
            'action_label' => $this->action->label(app()->getLocale()),
            'notes'     => $this->notes,
            'component' => new MachineComponentResource($this->whenLoaded('component')),
            'created_at' => $this->created_at,
        ];
    }
}
