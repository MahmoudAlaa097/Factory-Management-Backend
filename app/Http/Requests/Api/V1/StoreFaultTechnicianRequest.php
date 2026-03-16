<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreFaultTechnicianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manageTechnicians', $this->route('fault'));
    }

    public function rules(): array
    {
        return [
            'technician_id' => [
                'required',
                'integer',
                'exists:employees,id',
            ],
        ];
    }
}
