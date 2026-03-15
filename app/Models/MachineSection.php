<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MachineSection extends Model
{
    protected $fillable = [
        'name',
    ];

    // Relationships
    public function machineTypes(): BelongsToMany
    {
        return $this->belongsToMany(MachineType::class)->withTimestamps();
    }

    public function components(): HasMany
    {
        return $this->hasMany(MachineComponent::class);
    }
}
