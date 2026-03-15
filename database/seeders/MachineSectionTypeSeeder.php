<?php

namespace Database\Seeders;

use App\Models\MachineSection;
use App\Models\MachineType;

class MachineSectionTypeSeeder extends RelationalSeeder
{
    protected string $jsonFile = 'machine_section_types.json';

    public function run(): void
    {
        $data     = $this->loadJson();
        $types    = MachineType::all()->keyBy('name');
        $sections = MachineSection::all()->keyBy('name');

        collect($data)->each(function ($record) use ($types, $sections) {
            $sectionIds = collect($record['sections'])
                ->map(fn($name) => $sections[$name]->id)
                ->toArray();

            $types[$record['machine_type']]->sections()->attach($sectionIds);
        });
    }
}
