<?php

namespace App\Modules\BOK\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportAnnual extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'year',
        'status',
        'totals',
        'exports',
        'generated_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'year' => 'integer',
        'totals' => 'array',
        'exports' => 'array',
        'generated_at' => 'datetime',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}

