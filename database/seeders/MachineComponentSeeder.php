<?php

namespace Database\Seeders;

use App\Models\ComponentType;
use App\Models\MachineComponent;
use App\Models\MachineSection;

class MachineComponentSeeder extends RelationalSeeder
{
    protected string $jsonFile = 'machine_components.json';

    public function run(): void
    {
        $data           = $this->loadJson();
        $sections       = MachineSection::all()->keyBy('name');
        $componentTypes = ComponentType::all()->keyBy('name');

        collect($data)->each(fn($record) => MachineComponent::create([
            'machine_section_id' => $sections[$record['section']]->id,
            'component_type_id'  => $componentTypes[$record['component_type']]->id,
            'name'               => $record['name'],
        ]));
    }
}
