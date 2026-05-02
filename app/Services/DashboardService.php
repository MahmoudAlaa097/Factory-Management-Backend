<?php

namespace App\Services;

use App\Models\Fault;
use App\Models\Machine;
use App\Models\Employee;
use App\Models\User;
use App\Enums\FaultStatus;
use App\Support\UserContext;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardService
{
    // -----------------------------------------------------------------------
    // Scoping
    // -----------------------------------------------------------------------

    private function faultScope(User $user)
    {
        $query = Fault::query();

        if (UserContext::isAdmin($user)) {
            return $query;
        }

        if (UserContext::isMaintenance($user)) {
            return $query->where('maintenance_management_id', $user->employee->management_id);
        }

        if (UserContext::isProduction($user)) {
            return $query->where('division_id', $user->employee->division_id);
        }

        return $query->whereRaw('1 = 0');
    }

    private function machineScope(User $user)
    {
        $query = Machine::query();

        if (UserContext::isAdmin($user)) {
            return $query;
        }

        if (UserContext::isMaintenance($user)) {
            return $query;
        }

        if (UserContext::isProduction($user)) {
            return $query->where('division_id', $user->employee->division_id);
        }

        return $query->whereRaw('1 = 0');
    }

    private function technicianScope(User $user)
    {
        $query = Employee::query()->where('role', 'technician');

        if (UserContext::isAdmin($user)) {
            return $query;
        }

        return $query->where('management_id', $user->employee->management_id);
    }

    private function activeFaultStatuses(): array
    {
        return [
            FaultStatus::Open->value,
            FaultStatus::InProgress->value,
        ];
    }

    // -----------------------------------------------------------------------
    // KPIs
    // -----------------------------------------------------------------------

    public function kpis(User $user, Carbon $dateFrom, Carbon $dateTo): array
    {
        $base = $this->faultScope($user);

        $totalFaults = (clone $base)
            ->whereBetween('reported_at', [$dateFrom, $dateTo])
            ->count();

        $openFaults = (clone $base)
            ->whereIn('status', $this->activeFaultStatuses())
            ->count();

        $resolvedThisPeriod = (clone $base)
            ->whereBetween('resolved_at', [$dateFrom, $dateTo])
            ->whereNotNull('resolved_at')
            ->count();

        $avgResolutionTime = (clone $base)
            ->whereBetween('resolved_at', [$dateFrom, $dateTo])
            ->whereNotNull('technician_started_at')
            ->whereNotNull('resolved_at')
            ->selectRaw('AVG(EXTRACT(EPOCH FROM (resolved_at - technician_started_at)) / 60) as avg_minutes')
            ->value('avg_minutes');

        $mostFaultyMachine = (clone $base)
            ->whereBetween('reported_at', [$dateFrom, $dateTo])
            ->selectRaw('machine_id, COUNT(*) as fault_count')
            ->groupBy('machine_id')
            ->orderByDesc('fault_count')
            ->first();

        return [
            'total_faults'         => $totalFaults,
            'open_faults'          => $openFaults,
            'resolved_this_period' => $resolvedThisPeriod,
            'avg_resolution_time'  => $avgResolutionTime ? (int) round($avgResolutionTime) : null,
            'most_faulty_machine'  => $mostFaultyMachine ? [
                'id'          => $mostFaultyMachine->machine_id,
                'number'      => $mostFaultyMachine->machine?->number,
                'fault_count' => $mostFaultyMachine->fault_count,
            ] : null,
        ];
    }

    // -----------------------------------------------------------------------
    // Machines
    // -----------------------------------------------------------------------

    public function machines(User $user, Carbon $dateFrom, Carbon $dateTo): LengthAwarePaginator
    {
        $faultyThreshold = config('dashboard.machine_status.faulty');
        $atRiskThreshold = config('dashboard.machine_status.at_risk');
        $activeStatuses  = $this->activeFaultStatuses();

        return $this->machineScope($user)
            ->with(['division:id,name'])
            ->withCount([
                'faults as active_faults' => fn ($q) =>
                    $q->whereIn('status', $activeStatuses),

                'faults as total_faults_this_period' => fn ($q) =>
                    $q->whereBetween('reported_at', [$dateFrom, $dateTo]),
            ])
            ->withMax(
                ['faults as last_fault_at' => fn ($q) =>
                    $q->whereBetween('reported_at', [$dateFrom, $dateTo])],
                'reported_at'
            )
            ->addSelect([
                'avg_resolution_time' => Fault::selectRaw(
                    'AVG(EXTRACT(EPOCH FROM (resolved_at - technician_started_at)) / 60)'
                )
                ->whereColumn('machine_id', 'machines.id')
                ->whereBetween('resolved_at', [$dateFrom, $dateTo])
                ->whereNotNull('technician_started_at')
                ->whereNotNull('resolved_at'),
            ])
            ->paginate(request('per_page', 15))
            ->through(function ($machine) use ($faultyThreshold, $atRiskThreshold, $activeStatuses) {
                $score = Fault::whereIn('status', $activeStatuses)
                    ->where('machine_id', $machine->id)
                    ->selectRaw("SUM(EXTRACT(EPOCH FROM (NOW() - reported_at)) / 3600) as score")
                    ->value('score') ?? 0;

                $machine->status = match (true) {
                    $machine->active_faults === 0 => 'healthy',
                    $score <= $faultyThreshold    => 'faulty',
                    $score <= $atRiskThreshold    => 'at_risk',
                    default                       => 'critical',
                };

                $machine->score                = round($score, 2);
                $machine->avg_resolution_time  = $machine->avg_resolution_time
                    ? (int) round($machine->avg_resolution_time)
                    : null;

                return $machine;
            });
    }

    // -----------------------------------------------------------------------
    // Technicians
    // -----------------------------------------------------------------------

    public function technicians(User $user): Collection
    {
        $monthStart = now()->startOfMonth();
        $now        = now();

        return $this->technicianScope($user)
            ->get()
            ->map(function (Employee $technician) use ($monthStart, $now) {
                $base = Fault::whereHas('technicians', fn ($q) =>
                    $q->where('employees.id', $technician->id)
                );

                $activeFaults = (clone $base)
                    ->whereIn('status', $this->activeFaultStatuses())
                    ->count();

                $resolvedThisMonth = (clone $base)
                    ->whereBetween('resolved_at', [$monthStart, $now])
                    ->whereNotNull('resolved_at')
                    ->count();

                $avgResolutionTime = (clone $base)
                    ->whereNotNull('technician_started_at')
                    ->whereNotNull('resolved_at')
                    ->selectRaw('AVG(EXTRACT(EPOCH FROM (resolved_at - technician_started_at)) / 60) as avg_minutes')
                    ->value('avg_minutes');

                $avgResponseTime = (clone $base)
                    ->whereNotNull('technician_started_at')
                    ->selectRaw('AVG(EXTRACT(EPOCH FROM (technician_started_at - reported_at)) / 60) as avg_minutes')
                    ->value('avg_minutes');

                return [
                    'id'                  => $technician->id,
                    'name'                => $technician->name,
                    'active_faults'       => $activeFaults,
                    'resolved_this_month' => $resolvedThisMonth,
                    'avg_resolution_time' => $avgResolutionTime ? (int) round($avgResolutionTime) : null,
                    'avg_response_time'   => $avgResponseTime  ? (int) round($avgResponseTime)   : null,
                ];
            });
    }

    // -----------------------------------------------------------------------
    // Activity
    // -----------------------------------------------------------------------

    public function activity(User $user): LengthAwarePaginator
    {
        $since = now()->subDays(config('dashboard.activity_days', 7))->startOfDay();

        return $this->faultScope($user)
            ->with([
                'machine:id,number',
                'reporter:id,name',
            ])
            ->where(function ($q) use ($since) {
                $q->where('reported_at', '>=', $since)
                  ->orWhere('technician_started_at', '>=', $since)
                  ->orWhere('resolved_at', '>=', $since)
                  ->orWhere('operator_accepted_at', '>=', $since)
                  ->orWhere('maintenance_approved_at', '>=', $since)
                  ->orWhere('closed_at', '>=', $since);
            })
            ->orderByDesc('updated_at')
            ->paginate(config('dashboard.activity_per_page', 15));
    }
}
