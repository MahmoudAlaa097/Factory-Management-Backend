<?php

namespace App\Models;

use App\Enums\ComponentAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaultComponent extends Model
{
    protected $fillable = [
        'fault_id',
        'machine_component_id',
        'action',
        'notes',
    ];

    protected $casts = [
        'action' => ComponentAction::class,
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
