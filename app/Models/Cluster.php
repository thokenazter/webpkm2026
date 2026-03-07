<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all staff members belonging to this cluster.
     */
    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class)->orderBy('order');
    }

    /**
     * Get active staff members for this cluster.
     */
    public function activeStaff(): HasMany
    {
        return $this->hasMany(Staff::class)
            ->where('is_active', true)
            ->orderBy('is_leader', 'desc')
            ->orderBy('order');
    }

    /**
     * Scope to get only active clusters.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order clusters by their order field.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
