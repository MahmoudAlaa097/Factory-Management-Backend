<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComponentReplacement extends Model
{
    protected $fillable = [
        'fault_id',
        'machine_id',
        'machine_component_id',
        'replaced_by',
        'is_new',
        'replaced_at',
    ];

    protected $casts = [
        'is_new'      => 'boolean',
        'replaced_at' => 'datetime',
    ];

    // Relationships
    public function fault(): BelongsTo
    {
        return $this->belongsTo(Fault::class);
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(MachineComponent::class, 'machine_component_id');
    }

    public function replacedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'replaced_by');
    }
}
