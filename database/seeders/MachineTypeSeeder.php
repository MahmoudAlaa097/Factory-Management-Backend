<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\MachineType;

class MachineTypeSeeder extends RelationalSeeder
{
    protected string $jsonFile = 'machine_types.json';

    public function run(): void
    {
        $data      = $this->loadJson();
        $divisions = Division::all()->keyBy('name');

        collect($data)->each(fn($record) => MachineType::create([
            'division_id' => $divisions[$record['division']]->id,
            'name'        => $record['name'],
        ]));
    }
}
