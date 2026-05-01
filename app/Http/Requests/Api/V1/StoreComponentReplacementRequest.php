<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreComponentReplacementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('logReplacement', $this->route('fault'));
    }

    public function rules(): array
    {
        return [
            'machine_component_id' => ['required', 'integer', 'exists:machine_components,id'],
            'is_new'               => ['required', 'boolean'],
            'replaced_at'          => ['required', 'date'],
        ];
    }
}
