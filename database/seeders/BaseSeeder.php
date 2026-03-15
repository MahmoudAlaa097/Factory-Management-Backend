<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

abstract class BaseSeeder extends Seeder
{
    protected string $model;
    protected string $jsonFile;

    public function run(): void
    {
        collect($this->loadJson())
            ->each(fn($record) => $this->model::create($record));
    }

    protected function loadJson(): array
    {
        return json_decode(
            file_get_contents(database_path("data/{$this->jsonFile}")),
            true
        );
    }
}
