<?php

namespace App\Services;

use App\Enums\ComponentAction;
use App\Http\Requests\Api\V1\StoreFaultComponentRequest;
use App\Models\Fault;
use App\Models\FaultComponent;
use App\Models\ComponentReplacement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FaultComponentService
{
    public function store(StoreFaultComponentRequest $request, Fault $fault, User $user): FaultComponent
    {
        $componentId = $request->machine_component_id;

        // Ensure component belongs to this machine's type
        $validSectionIds = $fault->machine->machineType
            ->sections()
            ->pluck('machine_sections.id');

        $component = \App\Models\MachineComponent::findOrFail($componentId);

        if (! $validSectionIds->contains($component->machine_section_id)) {
            throw ValidationException::withMessages([
                'machine_component_id' => ['Component does not belong to this machine type.'],
            ]);
        }

        if ($fault->components()->where('machine_component_id', $componentId)->exists()) {
            throw ValidationException::withMessages([
                'machine_component_id' => ['Component is already attached to this fault.'],
            ]);
        }

        return DB::transaction(function () use ($request, $fault, $user, $componentId) {
            $faultComponent = FaultComponent::create([
                'fault_id'             => $fault->id,
                'machine_component_id' => $componentId,
                'action'               => $request->action,
                'notes'                => $request->notes,
            ]);

            if (ComponentAction::from($request->action)->isReplacement()) {
                ComponentReplacement::create([
                    'fault_id'             => $fault->id,
                    'machine_id'           => $fault->machine_id,
                    'machine_component_id' => $componentId,
                    'replaced_by'          => $user->employee->id,
                    'is_new'               => $request->is_new,
                    'replaced_at'          => $request->replaced_at,
                ]);
            }

            return $faultComponent;
        });
    }
}
