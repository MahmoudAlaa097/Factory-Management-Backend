<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaultTechnician extends Pivot
{
    protected $table = 'fault_technicians';

    protected $fillable = [
        'fault_id',
        'technician_id',
        'assigned_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    // Relationships
    public function fault(): BelongsTo
    {
        return $this->belongsTo(Fault::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'technician_id');
    }
}
