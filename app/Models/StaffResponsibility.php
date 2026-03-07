<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffResponsibility extends Model
{
    protected $fillable = [
        'staff_id',
        'category',
        'title',
    ];

    /**
     * Get the staff member this responsibility belongs to.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get human-readable category label.
     */
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'admin_app' => 'Admin Aplikasi',
            'koordinator' => 'Koordinator',
            'laporan' => 'Laporan SP2TP',
            'jaringan' => 'Jaringan Pelayanan',
            'ruangan' => 'Penanggung Jawab Ruangan',
            'program' => 'PJ Program',
            default => $this->category,
        };
    }
}
