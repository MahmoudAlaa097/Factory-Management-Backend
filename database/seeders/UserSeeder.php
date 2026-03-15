<?php

namespace Database\Seeders;

use App\Models\User;

class UserSeeder extends RelationalSeeder
{
    protected string $jsonFile = 'users.json';

    public function run(): void
    {
        collect($this->loadJson())->each(fn($record) => User::create([
            'username' => $record['username'],
            'password' => 'password',
        ]));
    }
}
