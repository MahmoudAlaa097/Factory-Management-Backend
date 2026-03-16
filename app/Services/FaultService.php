<?php

namespace App\Services;

use App\Enums\EmployeeRole;
use App\Enums\FaultStatus;
use App\Models\Fault;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\Api\V1\StoreFaultRequest;
use App\Models\Machine;
use App\Models\Employee;


class FaultService extends BaseService
{
    protected string $model          = Fault::class;
    protected array $allowedIncludes = [
        'machine',
        'division',
        'maintenanceManagement',
        'reporter',
        'maintenanceApprover',
        'closer',
        'technicians',
        'components',
        'replacements',
    ];
    protected array $allowedSorts    = [
        'id',
        'status',
        'reported_at',
        'closed_at',
    ];

    protected function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('machine_id'),
            AllowedFilter::exact('division_id'),
            AllowedFilter::exact('maintenance_management_id'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('reported_by'),
        ];
    }

    public function listForUser(User $user): LengthAwarePaginator
    {
        $employee = $user->employee->load('management');

        return QueryBuilder::for(Fault::class)
            ->allowedIncludes($this->allowedIncludes)
            ->allowedFilters($this->getAllowedFilters())
            ->allowedSorts($this->allowedSorts)
            ->when(!$employee->role->isAdmin(), function ($query) use ($employee) {

                // Maintenance — scoped to their management
                if ($employee->management->type->isMaintenance()) {
                    $query->where('maintenance_management_id', $employee->management_id);

                    // Technician — open/in_progress or own assigned faults
                    if ($employee->role->is(EmployeeRole::Technician)) {
                        $query->where(function ($q) use ($employee) {
                            $q->whereIn('status', [
                                FaultStatus::Open->value,
                                FaultStatus::InProgress->value,
                            ])->orWhereHas('technicians', fn($q) =>
                                $q->where('employees.id', $employee->id)
                            );
                        });
                    }
                }

                // Production — scoped to their division
                if ($employee->management->type->isProduction()) {
                    $query->where('division_id', $employee->division_id);

                    // Operator — own faults only
                    if ($employee->role->is(EmployeeRole::Operator)) {
                        $query->where('reported_by', $employee->id);
                    }
                }
            })
            ->paginate(request('per_page', 15));
    }

    public function show(\Illuminate\Database\Eloquent\Model $model): \Illuminate\Database\Eloquent\Model
    {
        return QueryBuilder::for(Fault::class)
            ->allowedIncludes($this->allowedIncludes)
            ->with('technicians')
            ->findOrFail($model->id);
    }

    public function report(StoreFaultRequest $request, User $user): Fault
    {
        $employee = $user->employee;
        $machine  = Machine::findOrFail($request->machine_id);

        return Fault::create([
            'machine_id'                => $machine->id,
            'division_id'               => $machine->division_id,
            'maintenance_management_id' => $request->maintenance_management_id,
            'reported_by'               => $employee->id,
            'status'                    => FaultStatus::Open,
            'description'               => $request->description,
            'reported_at'               => now(),
        ]);
    }

    public function respond(Fault $fault, User $user): Fault
    {
        $employee = $user->employee;

        $fault->update([
            'status'                => FaultStatus::InProgress,
            'technician_started_at' => now(),
        ]);

        $fault->technicians()->attach($employee->id, [
            'assigned_at' => now(),
        ]);

        return $fault->fresh();
    }

    public function resolve(Fault $fault): Fault
    {
        $fault->update([
            'status'      => FaultStatus::Resolved,
            'resolved_at' => now(),
        ]);

        return $fault->fresh();
    }

    public function accept(Fault $fault): Fault
    {
        $fault->update([
            'status'               => FaultStatus::OperatorAccepted,
            'operator_accepted_at' => now(),
        ]);

        return $fault->fresh();
    }

    public function approveMaintenance(Fault $fault, User $user): Fault
    {
        $fault->update([
            'status'                  => FaultStatus::MaintenanceApproved,
            'maintenance_approved_by' => $user->employee->id,
            'maintenance_approved_at' => now(),
        ]);

        return $fault->fresh();
    }

    public function close(Fault $fault, User $user): Fault
    {
        $fault->update([
            'status'        => FaultStatus::Closed,
            'closed_by'     => $user->employee->id,
            'closed_at'     => now(),
            'time_consumed' => (int) round($fault->reported_at->diffInMinutes(now())),
        ]);

        return $fault->fresh();
    }

    public function assignTechnician(Fault $fault, int $technicianId): Fault
    {
        $technician = Employee::findOrFail($technicianId);

        // Must be from correct maintenance management
        if ($technician->management_id !== $fault->maintenance_management_id) {
            throw new AuthorizationException(
                'Technician must belong to the correct maintenance management.'
            );
        }

        // Prevent duplicate assignment
        if ($fault->technicians()->where('employees.id', $technicianId)->exists()) {
            throw ValidationException::withMessages([
                'technician_id' => ['Technician is already assigned to this fault.'],
            ]);
        }

        $fault->technicians()->attach($technicianId, [
            'assigned_at' => now(),
        ]);

        return $fault->fresh();
    }

    public function unassignTechnician(Fault $fault, \App\Models\Employee $employee): Fault
    {
        // Cannot unassign original responding technician
        $originalTechnicianId = $fault->technicians()
            ->orderBy('fault_technicians.assigned_at')
            ->first()?->id;

        if ($originalTechnicianId === $employee->id) {
            throw ValidationException::withMessages([
                'technician_id' => ['Cannot unassign the original responding technician.'],
            ]);
        }

        $fault->technicians()->detach($employee->id);

        return $fault->fresh();
    }
}
