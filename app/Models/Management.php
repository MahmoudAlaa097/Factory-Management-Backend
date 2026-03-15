<?php

namespace App\Models;

use App\Enums\ManagementType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Management extends Model
{
    protected $table = 'managements';

    protected $fillable = [
        'type',
    ];

    protected $casts = [
        'type' => ManagementType::class,
    ];

    // Relationships
    public function divisions(): HasMany
    {
        return $this->hasMany(Division::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
