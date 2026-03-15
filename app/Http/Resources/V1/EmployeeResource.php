<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'code'       => $this->code,
            'role'       => $this->role,
            'is_active'  => $this->is_active,
            'user'       => new UserResource($this->whenLoaded('user')),
            'management' => new ManagementResource($this->whenLoaded('management')),
            'division'   => new DivisionResource($this->whenLoaded('division')),
            'created_at' => $this->created_at,
        ];
    }
}
