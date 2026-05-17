<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkOrder;

class WorkOrderSeeder extends BaseSeeder
{
    protected string $model     = WorkOrder::class;
    protected string $jsonFile  = 'work_orders.json';

    public function run(): void
    {
        $records = $this->loadJson();

        foreach ($records as $record) {
            $technician_ids       = $record['technician_ids'];
            $affected_components  = $record['affected_components'] ?? [];

            unset($record['technician_ids'], $record['affected_components']);

            $workOrder = WorkOrder::create($record);
            $workOrder->technicians()->attach($technician_ids);

            if (!empty($affected_components)) {
                $rows = array_map(fn ($c) => [
                    'work_order_id'        => $workOrder->id,
                    'machine_section_id'   => $c['section_id'],
                    'machine_component_id' => $c['component_id'],
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ], $affected_components);

                $workOrder->components()->insert($rows);
            }
        }
    }
}
