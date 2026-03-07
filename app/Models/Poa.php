<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poa extends Model
{
    protected $fillable = [
        'year', 'annual_budget_id', 'rab_id', 'nomor_surat', 'kegiatan', 'output_target', 'schedule', 'item_progress', 'planned_total', 'created_by'
    ];

    protected $casts = [
        'year' => 'integer',
        'planned_total' => 'decimal:2',
        'schedule' => 'array',
        'item_progress' => 'array',
    ];

    public function budget(): BelongsTo
    {
        return $this->belongsTo(AnnualBudget::class, 'annual_budget_id');
    }

    public function rab(): BelongsTo
    {
        return $this->belongsTo(Rab::class, 'rab_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(PoaParticipant::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ActivitySchedule::class);
    }

    /**
     * Get schedule for a specific month/year
     */
    public function getScheduleFor(int $month, int $year): ?ActivitySchedule
    {
        return $this->schedules()->where('month', $month)->where('year', $year)->first();
    }
}
