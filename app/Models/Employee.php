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

    // -----------------------------------------------------------------------
    // Relationships
    // -----------------------------------------------------------------------

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

    // -----------------------------------------------------------------------
    // Role helpers
    // -----------------------------------------------------------------------

    public function isAdmin(): bool
    {
        return $this->role->isAdmin();
    }

    public function isManager(): bool
    {
        return $this->role->isManager();
    }

    public function isEngineer(): bool
    {
        return $this->role->isEngineer();
    }

    public function isSupervisor(): bool
    {
        return $this->role->isSupervisor();
    }

    public function isTechnician(): bool
    {
        return $this->role->isTechnician();
    }

    public function isOperator(): bool
    {
        return $this->role->isOperator();
    }

    // -----------------------------------------------------------------------
    // Management-type helpers
    // -----------------------------------------------------------------------

    public function isMaintenance(): bool
    {
        return (bool) $this->management?->type->isMaintenance();
    }

    public function isProduction(): bool
    {
        return (bool) $this->management?->type->isProduction();
    }

    // -----------------------------------------------------------------------
    // Combined role + management-type helpers
    // -----------------------------------------------------------------------

    public function isMaintenanceTechnician(): bool
    {
        return $this->isTechnician() && $this->isMaintenance();
    }

    public function isMaintenanceSupervisor(): bool
    {
        return $this->isSupervisor() && $this->isMaintenance();
    }

    public function isMaintenanceEngineer(): bool
    {
        return $this->isEngineer() && $this->isMaintenance();
    }

    public function isMaintenanceManager(): bool
    {
        return $this->isManager() && $this->isMaintenance();
    }

    public function isProductionOperator(): bool
    {
        return $this->isOperator() && $this->isProduction();
    }

    public function isProductionSupervisor(): bool
    {
        return $this->isSupervisor() && $this->isProduction();
    }

    public function isProductionEngineer(): bool
    {
        return $this->isEngineer() && $this->isProduction();
    }

    public function isProductionManager(): bool
    {
        return $this->isManager() && $this->isProduction();
    }
}
