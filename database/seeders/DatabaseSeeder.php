<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ManagementSeeder::class,
            DivisionSeeder::class,
            MachineSectionSeeder::class,
            ComponentTypeSeeder::class,
            MachineTypeSeeder::class,
            MachineSectionTypeSeeder::class,
            MachineSeeder::class,
            MachineComponentSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}
