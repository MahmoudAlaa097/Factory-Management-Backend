<?php

namespace Database\Seeders;

use App\Enums\FaultStatus;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Fault;
use App\Models\FaultComponent;
use App\Models\Machine;
use App\Models\Management;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FaultSeeder extends Seeder
{
    public function run(): void
    {
        $division              = Division::where('name', 'Automatic Assembly')->first();
        $electricalMaintenance = Management::where('type', 'electrical_maintenance')->first();

        $operator      = Employee::where('role', 'operator')->first();
        $supervisor    = Employee::where('role', 'supervisor')
            ->whereHas('management', fn ($q) => $q->where('type', 'production'))
            ->first();
        $technician1   = Employee::where('code', 1003)->first();
        $technician2   = Employee::where('code', 1004)->first();
        $maintSupervisor = Employee::where('code', 1006)->first();
        $maintEngineer   = Employee::where('code', 1008)->first();

        $machines = Machine::all();

        $now = Carbon::now();

        $faults = [
            // 1. Open — just reported
            [
                'machine'      => $machines->random(),
                'reported_by'  => $operator,
                'description'  => 'Machine stopped unexpectedly during cycle.',
                'reported_at'  => $now->copy()->subHours(1),
                'status'       => FaultStatus::Open,
            ],
            // 2. Open — reported 3 hours ago (at_risk territory)
            [
                'machine'      => $machines->random(),
                'reported_by'  => $operator,
                'description'  => 'Vibration sensor not responding.',
                'reported_at'  => $now->copy()->subHours(3),
                'status'       => FaultStatus::Open,
            ],
            // 3. Open — reported 5 hours ago (critical territory)
            [
                'machine'      => $machines->random(),
                'reported_by'  => $supervisor,
                'description'  => 'PLC error on startup.',
                'reported_at'  => $now->copy()->subHours(5),
                'status'       => FaultStatus::Open,
            ],
            // 4. In Progress
            [
                'machine'              => $machines->random(),
                'reported_by'          => $operator,
                'description'          => 'Encoder signal lost.',
                'reported_at'          => $now->copy()->subHours(4),
                'status'               => FaultStatus::InProgress,
                'technician_started_at'=> $now->copy()->subHours(3),
                'technician'           => $technician1,
            ],
            // 5. In Progress — two machines active
            [
                'machine'              => $machines->random(),
                'reported_by'          => $operator,
                'description'          => 'Inverter fault code F003.',
                'reported_at'          => $now->copy()->subHours(6),
                'status'               => FaultStatus::InProgress,
                'technician_started_at'=> $now->copy()->subHours(5),
                'technician'           => $technician2,
            ],
            // 6. Resolved
            [
                'machine'              => $machines->random(),
                'reported_by'          => $operator,
                'description'          => 'Track sensor misaligned.',
                'reported_at'          => $now->copy()->subDays(2)->subHours(3),
                'status'               => FaultStatus::Resolved,
                'technician_started_at'=> $now->copy()->subDays(2)->subHours(2),
                'resolved_at'          => $now->copy()->subDays(2)->subHour(),
                'resolution_notes'     => 'Realigned track sensor and tested cycle.',
                'time_consumed'        => 60,
                'technician'           => $technician1,
            ],
            // 7. Resolved
            [
                'machine'              => $machines->random(),
                'reported_by'          => $supervisor,
                'description'          => 'Cap sensor giving false triggers.',
                'reported_at'          => $now->copy()->subDays(3),
                'status'               => FaultStatus::Resolved,
                'technician_started_at'=> $now->copy()->subDays(3)->addHour(),
                'resolved_at'          => $now->copy()->subDays(3)->addHours(3),
                'resolution_notes'     => 'Replaced cap sensor.',
                'time_consumed'        => 120,
                'technician'           => $technician2,
            ],
            // 8. Operator Accepted
            [
                'machine'              => $machines->random(),
                'reported_by'          => $operator,
                'description'          => 'Motor overheating alarm.',
                'reported_at'          => $now->copy()->subDays(4),
                'status'               => FaultStatus::OperatorAccepted,
                'technician_started_at'=> $now->copy()->subDays(4)->addHour(),
                'resolved_at'          => $now->copy()->subDays(4)->addHours(2),
                'operator_accepted_at' => $now->copy()->subDays(4)->addHours(3),
                'resolution_notes'     => 'Cleaned motor cooling vents, replaced thermal sensor.',
                'time_consumed'        => 90,
                'technician'           => $technician1,
            ],
            // 9. Maintenance Approved
            [
                'machine'                  => $machines->random(),
                'reported_by'              => $operator,
                'description'              => 'Electronic board malfunction.',
                'reported_at'              => $now->copy()->subDays(5),
                'status'                   => FaultStatus::MaintenanceApproved,
                'technician_started_at'    => $now->copy()->subDays(5)->addHour(),
                'resolved_at'              => $now->copy()->subDays(5)->addHours(4),
                'operator_accepted_at'     => $now->copy()->subDays(5)->addHours(5),
                'maintenance_approved_at'  => $now->copy()->subDays(5)->addHours(6),
                'maintenance_approved_by'  => $maintSupervisor,
                'resolution_notes'         => 'Replaced electronic board.',
                'time_consumed'            => 180,
                'technician'               => $technician1,
            ],
            // 10. Closed
            [
                'machine'                  => $machines->random(),
                'reported_by'              => $operator,
                'description'              => 'Switches damaged.',
                'reported_at'              => $now->copy()->subDays(6),
                'status'                   => FaultStatus::Closed,
                'technician_started_at'    => $now->copy()->subDays(6)->addHour(),
                'resolved_at'              => $now->copy()->subDays(6)->addHours(2),
                'operator_accepted_at'     => $now->copy()->subDays(6)->addHours(3),
                'maintenance_approved_at'  => $now->copy()->subDays(6)->addHours(4),
                'maintenance_approved_by'  => $maintSupervisor,
                'closed_at'                => $now->copy()->subDays(6)->addHours(5),
                'closed_by'                => $maintEngineer,
                'resolution_notes'         => 'Replaced damaged switches.',
                'time_consumed'            => 60,
                'technician'               => $technician2,
            ],
            // 11–20: Mix of open/in_progress/resolved spread across the month
            ...$this->generateBulkFaults($machines, $operator, $technician1, $technician2, $now),
        ];

        foreach ($faults as $data) {
            $fault = Fault::create([
                'machine_id'               => $data['machine']->id,
                'division_id'              => $division->id,
                'reported_by'              => $data['reported_by']->id,
                'maintenance_management_id'=> $electricalMaintenance->id,
                'status'                   => $data['status'],
                'description'              => $data['description'],
                'reported_at'              => $data['reported_at'],
                'technician_started_at'    => $data['technician_started_at'] ?? null,
                'resolved_at'              => $data['resolved_at'] ?? null,
                'operator_accepted_at'     => $data['operator_accepted_at'] ?? null,
                'maintenance_approved_at'  => $data['maintenance_approved_at'] ?? null,
                'maintenance_approved_by'  => $data['maintenance_approved_by']?->id ?? null,
                'closed_at'                => $data['closed_at'] ?? null,
                'closed_by'                => $data['closed_by']?->id ?? null,
                'resolution_notes'         => $data['resolution_notes'] ?? null,
                'time_consumed'            => $data['time_consumed'] ?? null,
            ]);

            // Attach primary technician
            if (isset($data['technician'])) {
                $fault->technicians()->attach($data['technician']->id, [
                    'assigned_at' => $data['technician_started_at'] ?? $data['reported_at'],
                ]);
            }
        }
    }

    private function generateBulkFaults($machines, $operator, $technician1, $technician2, $now): array
    {
        $faults = [];

        for ($i = 1; $i <= 10; $i++) {
            $reportedAt = $now->copy()->subDays(rand(1, 28))->subHours(rand(0, 8));
            $startedAt  = $reportedAt->copy()->addMinutes(rand(30, 120));
            $resolvedAt = $startedAt->copy()->addMinutes(rand(30, 240));

            $faults[] = [
                'machine'              => $machines->random(),
                'reported_by'          => $operator,
                'description'          => "Fault #{$i} — automated seed entry.",
                'reported_at'          => $reportedAt,
                'status'               => FaultStatus::Closed,
                'technician_started_at'=> $startedAt,
                'resolved_at'          => $resolvedAt,
                'operator_accepted_at' => $resolvedAt->copy()->addHour(),
                'maintenance_approved_at' => $resolvedAt->copy()->addHours(2),
                'closed_at'            => $resolvedAt->copy()->addHours(3),
                'resolution_notes'     => 'Routine fix.',
                'time_consumed'        => (int) $startedAt->diffInMinutes($resolvedAt),
                'technician'           => $i % 2 === 0 ? $technician1 : $technician2,
            ];
        }

        return $faults;
    }
}
