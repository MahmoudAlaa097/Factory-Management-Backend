<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MachineComponent extends Model
{
    protected $fillable = [
        'machine_section_id',
        'component_type_id',
        'name',
    ];

    // Relationships
    public function section(): BelongsTo
    {
        return $this->belongsTo(MachineSection::class, 'machine_section_id');
    }

    public function componentType(): BelongsTo
    {
        return $this->belongsTo(ComponentType::class);
    }

    public function faultComponents(): HasMany
    {
        return $this->hasMany(FaultComponent::class);
    }

    public function oldReplacements(): HasMany
    {
        return $this->hasMany(ComponentReplacement::class, 'old_component_id');
    }

    public function newReplacements(): HasMany
    {
        return $this->hasMany(ComponentReplacement::class, 'new_component_id');
    }
}
