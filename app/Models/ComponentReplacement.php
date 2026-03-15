<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComponentReplacement extends Model
{
    protected $fillable = [
        'fault_id',
        'machine_id',
        'old_component_id',
        'new_component_id',
        'replaced_by',
        'replaced_at',
    ];

    protected $casts = [
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

    public function oldComponent(): BelongsTo
    {
        return $this->belongsTo(MachineComponent::class, 'old_component_id');
    }

    public function newComponent(): BelongsTo
    {
        return $this->belongsTo(MachineComponent::class, 'new_component_id');
    }

    public function replacedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'replaced_by');
    }
}
