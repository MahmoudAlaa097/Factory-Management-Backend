<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ListMachineTypesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'include'          => ['sometimes', 'string'],
            'filter.division_id' => ['sometimes', 'integer', 'exists:divisions,id'],
            'sort'             => ['sometimes', 'string'],
            'per_page'         => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
