<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'cluster_id',
        'photo',
        'is_leader',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_leader' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the cluster this staff member belongs to.
     */
    public function cluster(): BelongsTo
    {
        return $this->belongsTo(Cluster::class);
    }

    /**
     * Get staff responsibilities.
     */
    public function responsibilities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StaffResponsibility::class);
    }

    /**
     * Get the photo URL with default avatar fallback.
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            // Direct public path (e.g. /images/staff/nama.jpg)
            if (str_starts_with($this->photo, '/')) {
                return $this->photo;
            }

            // Storage disk path
            if (Storage::disk('public')->exists($this->photo)) {
                return Storage::url($this->photo);
            }
        }

        // Generate initials-based avatar using UI Avatars
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&background=059669&color=ffffff&size=200&font-size=0.4&bold=true";
    }

    /**
     * Scope to get only active staff.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get leaders first.
     */
    public function scopeLeadersFirst($query)
    {
        return $query->orderBy('is_leader', 'desc')->orderBy('order');
    }
}
