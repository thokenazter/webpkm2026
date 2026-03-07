<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LpjParticipant extends Model
{
    use LogsActivity;

    protected $fillable = [
        'lpj_id',
        'employee_id',
        'borrowed_employee_id',
        'credited_employee_id',
        'role',
        'lama_tugas_hari',
        'transport_amount',
        'per_diem_rate',
        'per_diem_days',
        'per_diem_amount',
        'total_amount',
    ];

    protected $casts = [
        'transport_amount' => 'decimal:2',
        'per_diem_rate' => 'decimal:2',
        'per_diem_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function lpj(): BelongsTo
    {
        return $this->belongsTo(Lpj::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function borrowedEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'borrowed_employee_id');
    }

    public function creditedEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'credited_employee_id');
    }

    public function getDocumentEmployeeAttribute(): ?Employee
    {
        // Prefer explicit borrowed employee when present; fallback to stored employee relation
        if ($this->relationLoaded('borrowedEmployee') && $this->borrowedEmployee) {
            return $this->borrowedEmployee;
        }

        if (!empty($this->borrowed_employee_id)) {
            return $this->borrowedEmployee()->first() ?: $this->employee;
        }

        return $this->employee;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['role', 'lama_tugas_hari', 'transport_amount', 'per_diem_amount', 'total_amount'])
            ->logOnlyDirty();
    }
}
