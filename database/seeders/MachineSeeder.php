<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Machine;
use App\Models\MachineType;

class MachineSeeder extends RelationalSeeder
{
    protected string $jsonFile = 'machines.json';

    public function run(): void
    {
        $data      = $this->loadJson();
        $divisions = Division::all()->keyBy('name');
        $types     = MachineType::all()->keyBy('name');

        collect($data)->each(fn($record) => Machine::create([
            'division_id'     => $divisions[$record['division']]->id,
            'machine_type_id' => $types[$record['machine_type']]->id,
            'number'          => $record['number'],
            'is_active'       => true,
        ]));
    }
}
