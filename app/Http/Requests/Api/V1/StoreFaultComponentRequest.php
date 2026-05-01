<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\ComponentAction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreFaultComponentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manageComponents', $this->route('fault'));
    }

    public function rules(): array
    {
        return [
            'machine_component_id' => [
                'required',
                'integer',
                'exists:machine_components,id',
            ],
            'action' => [
                'required',
                new Enum(ComponentAction::class),
            ],
            'notes'       => ['sometimes', 'nullable', 'string', 'max:500'],

            // Only required when action = replaced
            'is_new'      => ['required_if:action,replaced', 'boolean'],
            'replaced_at' => ['required_if:action,replaced', 'date'],
        ];
    }
}
