<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

abstract class RelationalSeeder extends Seeder
{
    protected string $jsonFile;

    protected function loadJson(): array
    {
        return json_decode(
            file_get_contents(database_path("data/{$this->jsonFile}")),
            true
        );
    }
}
