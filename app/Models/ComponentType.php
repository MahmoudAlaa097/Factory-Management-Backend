<?php

namespace App\Models;

use App\Enums\ComponentCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComponentType extends Model
{
    protected $fillable = [
        'name',
        'category',
    ];

    protected $casts = [
        'category' => ComponentCategory::class,
    ];

    // Relationships
    public function machineComponents(): HasMany
    {
        return $this->hasMany(MachineComponent::class);
    }
}
