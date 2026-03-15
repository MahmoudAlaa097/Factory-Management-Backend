<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Machine extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'division_id',
        'machine_type_id',
        'number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function machineType(): BelongsTo
    {
        return $this->belongsTo(MachineType::class);
    }

    public function faults(): HasMany
    {
        return $this->hasMany(Fault::class);
    }

    public function componentReplacements(): HasMany
    {
        return $this->hasMany(ComponentReplacement::class);
    }
}
