<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ListMachineComponentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'include'                    => ['sometimes', 'string'],
            'filter.machine_section_id'  => ['sometimes', 'integer', 'exists:machine_sections,id'],
            'filter.component_type_id'   => ['sometimes', 'integer', 'exists:component_types,id'],
            'sort'                       => ['sometimes', 'string'],
            'per_page'                   => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
