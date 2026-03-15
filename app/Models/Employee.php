<?php

namespace App\Models;

use App\Enums\EmployeeRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'management_id',
        'division_id',
        'user_id',
        'name',
        'code',
        'role',
        'is_active',
    ];

    protected $casts = [
        'role'      => EmployeeRole::class,
        'is_active' => 'boolean',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function management(): BelongsTo
    {
        return $this->belongsTo(Management::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function reportedFaults(): HasMany
    {
        return $this->hasMany(Fault::class, 'reported_by');
    }

    public function approvedFaults(): HasMany
    {
        return $this->hasMany(Fault::class, 'maintenance_approved_by');
    }

    public function closedFaults(): HasMany
    {
        return $this->hasMany(Fault::class, 'closed_by');
    }

    public function assignedFaults(): BelongsToMany
    {
        return $this->belongsToMany(
            Fault::class,
            'fault_technicians',
            'technician_id',
            'fault_id'
        )
        ->withPivot('assigned_at')
        ->withTimestamps();
    }

    public function replacements(): HasMany
    {
        return $this->hasMany(ComponentReplacement::class, 'replaced_by');
    }
}
