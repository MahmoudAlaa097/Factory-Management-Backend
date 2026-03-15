<?php

namespace App\Models;

use App\Enums\FaultStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fault extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'machine_id',
        'division_id',
        'maintenance_management_id',
        'reported_by',
        'maintenance_approved_by',
        'closed_by',
        'status',
        'description',
        'reported_at',
        'technician_started_at',
        'resolved_at',
        'operator_accepted_at',
        'maintenance_approved_at',
        'closed_at',
        'time_consumed',
    ];

    protected $casts = [
        'status'                  => FaultStatus::class,
        'reported_at'             => 'datetime',
        'technician_started_at'   => 'datetime',
        'resolved_at'             => 'datetime',
        'operator_accepted_at'    => 'datetime',
        'maintenance_approved_at' => 'datetime',
        'closed_at'               => 'datetime',
    ];

    // Relationships
    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function maintenanceManagement(): BelongsTo
    {
        return $this->belongsTo(Management::class, 'maintenance_management_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reported_by');
    }

    public function maintenanceApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'maintenance_approved_by');
    }

    public function closer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'closed_by');
    }

    public function technicians(): BelongsToMany
    {
        return $this->belongsToMany(
            Employee::class,
            'fault_technicians',
            'fault_id',
            'technician_id'
        )
        ->withPivot('assigned_at')
        ->withTimestamps();
    }

    public function components(): HasMany
    {
        return $this->hasMany(FaultComponent::class);
    }

    public function replacements(): HasMany
    {
        return $this->hasMany(ComponentReplacement::class);
    }

    // Status helpers
    public function isOpen(): bool
    {
        return $this->status->is(FaultStatus::Open);
    }

    public function isInProgress(): bool
    {
        return $this->status->is(FaultStatus::InProgress);
    }

    public function isResolved(): bool
    {
        return $this->status->is(FaultStatus::Resolved);
    }

    public function isOperatorAccepted(): bool
    {
        return $this->status->is(FaultStatus::OperatorAccepted);
    }

    public function isMaintenanceApproved(): bool
    {
        return $this->status->is(FaultStatus::MaintenanceApproved);
    }

    public function isClosed(): bool
    {
        return $this->status->is(FaultStatus::Closed);
    }
}
