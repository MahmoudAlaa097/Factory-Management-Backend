<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Management;
use App\Policies\ManagementPolicy;
use App\Models\Division;
use App\Policies\DivisionPolicy;
use App\Models\Employee;
use App\Policies\EmployeePolicy;
use App\Models\MachineType;
use App\Policies\MachineTypePolicy;
use App\Models\Machine;
use App\Policies\MachinePolicy;
use App\Models\MachineSection;
use App\Policies\MachineSectionPolicy;
use App\Models\ComponentType;
use App\Policies\ComponentTypePolicy;
use App\Models\MachineComponent;
use App\Policies\MachineComponentPolicy;
use App\Models\Fault;
use App\Policies\FaultPolicy;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Management::class, ManagementPolicy::class);
        Gate::policy(Division::class, DivisionPolicy::class);
        Gate::policy(Employee::class, EmployeePolicy::class);
        Gate::policy(MachineType::class, MachineTypePolicy::class);
        Gate::policy(Machine::class, MachinePolicy::class);
        Gate::policy(MachineSection::class, MachineSectionPolicy::class);
        Gate::policy(ComponentType::class, ComponentTypePolicy::class);
        Gate::policy(MachineComponent::class, MachineComponentPolicy::class);
        Gate::policy(Fault::class, FaultPolicy::class);
    }
}
