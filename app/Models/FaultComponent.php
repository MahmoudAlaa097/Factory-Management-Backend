<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaultComponent extends Model
{
    protected $fillable = [
        'fault_id',
        'machine_component_id',
        'notes',
    ];

    // Relationships
    public function fault(): BelongsTo
    {
        return $this->belongsTo(Fault::class);
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(MachineComponent::class, 'machine_component_id');
    }
}
