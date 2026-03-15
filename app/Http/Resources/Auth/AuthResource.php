<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    private string $token;

    public function __construct($resource, string $token)
    {
        parent::__construct($resource);
        $this->token = $token;
    }

    public function toArray($request): array
    {
        return [
            'token' => $this->token,
            'user'  => [
                'id'       => $this->id,
                'username' => $this->username,
                'employee' => [
                    'id'         => $this->employee->id,
                    'name'       => $this->employee->name,
                    'code'       => $this->employee->code,
                    'role'       => $this->employee->role,
                    'management' => $this->employee->management->type,
                    'division'   => $this->employee->division->name,
                ],
            ],
        ];
    }
}
