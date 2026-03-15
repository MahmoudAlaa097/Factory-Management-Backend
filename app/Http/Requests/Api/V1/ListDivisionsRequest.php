<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ListDivisionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'include'                   => ['sometimes', 'string'],
            'filter.management_id'      => ['sometimes', 'integer', 'exists:managements,id'],
            'filter.parent_division_id' => ['sometimes', 'integer', 'exists:divisions,id'],
            'filter.is_active'          => ['sometimes', 'in:0,1,true,false'],
            'sort'                      => ['sometimes', 'string'],
            'per_page'                  => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
