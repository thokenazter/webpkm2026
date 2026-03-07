<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ActivitySchedule extends Model
{
    protected $fillable = [
        'poa_id',
        'type', // SPPT or SPPD
        'desa_count',
        'month',
        'year',
        'start_date',
        'end_date',
        'nomor_surat',
        'status',
        'created_by',
        'finalized_at',
        'finalized_by',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'finalized_at' => 'datetime',
        'month' => 'integer',
        'year' => 'integer',
        'desa_count' => 'integer',
    ];

    // ==================== Relationships ====================

    public function poa(): BelongsTo
    {
        return $this->belongsTo(Poa::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function finalizedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }

    // ==================== Scopes ====================

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeFinalized($query)
    {
        return $query->where('status', 'finalized');
    }

    public function scopeClaimed($query)
    {
        return $query->where('status', 'claimed');
    }

    public function scopeForMonth($query, int $month, int $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    // ==================== Accessors ====================

    /**
     * Get formatted date range like "01 s/d 03 Januari 2025"
     */
    public function getFormattedDateRangeAttribute(): string
    {
        Carbon::setLocale('id');
        
        if (!$this->start_date || !$this->end_date) {
            return '-';
        }

        if ($this->start_date->equalTo($this->end_date)) {
            return $this->start_date->translatedFormat('d F Y');
        }

        return $this->start_date->format('d') . ' s/d ' . $this->end_date->translatedFormat('d F Y');
    }

    /**
     * Get duration in days
     */
    public function getDurationDaysAttribute(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    // ==================== Helper Methods ====================

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isFinalized(): bool
    {
        return $this->status === 'finalized';
    }

    public function isClaimed(): bool
    {
        return $this->status === 'claimed';
    }

    public function canBeEdited(): bool
    {
        return $this->isDraft();
    }

    public function markAsFinalized(int $userId): void
    {
        $this->update([
            'status' => 'finalized',
            'finalized_at' => now(),
            'finalized_by' => $userId,
        ]);
    }

    public function markAsClaimed(): void
    {
        $this->update(['status' => 'claimed']);
    }
}
