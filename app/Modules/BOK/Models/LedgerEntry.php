<?php

namespace App\Modules\BOK\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LedgerEntry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'entry_date',
        'account_type',
        'description',
        'reference',
        'debit',
        'credit',
        'balance',
        'period_year',
        'period_month',
        'posted_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
        'balance' => 'decimal:2',
        'posted_at' => 'datetime',
        'period_year' => 'integer',
        'period_month' => 'integer',
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

