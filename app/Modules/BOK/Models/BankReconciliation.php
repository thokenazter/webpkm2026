<?php

namespace App\Modules\BOK\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankReconciliation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'year',
        'month',
        'statement_ending_balance',
        'ledger_ending_balance',
        'matched_count',
        'unmatched_count',
        'status',
        'data',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'statement_ending_balance' => 'decimal:2',
        'ledger_ending_balance' => 'decimal:2',
        'matched_count' => 'integer',
        'unmatched_count' => 'integer',
        'data' => 'array',
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

