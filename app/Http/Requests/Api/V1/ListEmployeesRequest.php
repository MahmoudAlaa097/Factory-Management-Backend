<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ListEmployeesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'include'            => ['sometimes', 'string'],
            'filter.management_id' => ['sometimes', 'integer', 'exists:managements,id'],
            'filter.division_id'   => ['sometimes', 'integer', 'exists:divisions,id'],
            'filter.role'          => ['sometimes', 'string'],
            'filter.is_active'     => ['sometimes', 'boolean'],
            'sort'               => ['sometimes', 'string'],
            'per_page'           => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
