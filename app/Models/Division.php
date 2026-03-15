<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Division extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'management_id',
        'parent_division_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function management(): BelongsTo
    {
        return $this->belongsTo(Management::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'parent_division_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Division::class, 'parent_division_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function machines(): HasMany
    {
        return $this->hasMany(Machine::class);
    }

    public function machineTypes(): HasMany
    {
        return $this->hasMany(MachineType::class);
    }

    public function faults(): HasMany
    {
        return $this->hasMany(Fault::class);
    }
}
