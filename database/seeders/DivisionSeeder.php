<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Management;

class DivisionSeeder extends RelationalSeeder
{
    protected string $jsonFile = 'divisions.json';

    public function run(): void
    {
        $data        = $this->loadJson();
        $managements = Management::all()->keyBy(fn($m) => $m->type->value);
        $divisions   = collect();

        collect($data)->each(function ($record) use ($managements, &$divisions) {
            $division = Division::create([
                'management_id'      => $managements[$record['management']]->id,
                'parent_division_id' => $record['parent']
                    ? $divisions->firstWhere('name', $record['parent'])->id
                    : null,
                'name'               => $record['name'],
                'is_active'          => true,
            ]);

            $divisions->push($division);
        });
    }
}
