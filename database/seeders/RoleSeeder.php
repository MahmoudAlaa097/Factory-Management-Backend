<?php

namespace Database\Seeders;

use App\Enums\EmployeeRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_managements',
            'view_divisions',
            'view_employees',
            'view_machine_types',
            'view_machines',
            'view_machine_sections',
            'view_component_types',
            'view_machine_components',
            'view_faults',
            'report_fault',
            'respond_fault',
            'resolve_fault',
            'accept_fault',
            'approve_fault',
            'close_fault',
        ];

        collect($permissions)->each(
            fn($permission) => Permission::create(['name' => $permission])
        );

        $roles = [
            EmployeeRole::Admin->value => $permissions,
            EmployeeRole::Manager->value => [
                'view_faults',
            ],
            EmployeeRole::Engineer->value => [
                'view_faults',
                'close_fault',
            ],
            EmployeeRole::Supervisor->value => [
                'view_faults',
                'report_fault',
                'approve_fault',
            ],
            EmployeeRole::Technician->value => [
                'view_faults',
                'respond_fault',
                'resolve_fault',
            ],
            EmployeeRole::Operator->value => [
                'view_faults',
                'report_fault',
                'accept_fault',
            ],
        ];

        collect($roles)->each(
            fn($permissions, $role) => Role::create(['name' => $role])
                ->givePermissionTo($permissions)
        );
    }
}
