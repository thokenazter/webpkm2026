<?php

namespace App\Modules\BOK\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TaxEntry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tax_type',
        'base_amount',
        'tax_amount',
        'status',
        'due_date',
        'paid_at',
        'verified_at',
        'evidence_path',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'base_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function source(): MorphTo
    {
        return $this->morphTo('source');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}

