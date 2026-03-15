<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class MachineType extends Model
{
    protected $fillable = [
        'division_id',
        'name',
        'model',
        'manufacturer',
        'specifications',
        'manual_url',
        'image_url',
    ];

    protected $casts = [
        'specifications' => 'array',
    ];

    // Relationships
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function machines(): HasMany
    {
        return $this->hasMany(Machine::class);
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(MachineSection::class)->withTimestamps();
    }

    public function components(): HasManyThrough
    {
        return $this->hasManyThrough(
            MachineComponent::class,
            MachineSection::class,
        );
    }
}
