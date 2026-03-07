<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetAllocation extends Model
{
    protected $fillable = [
        'annual_budget_id',
        'rab_id',
        'allocated_amount',
        'notes',
    ];

    protected $casts = [
        'allocated_amount' => 'decimal:2',
    ];

    public function budget(): BelongsTo
    {
        return $this->belongsTo(AnnualBudget::class, 'annual_budget_id');
    }

    public function rab(): BelongsTo
    {
        return $this->belongsTo(Rab::class, 'rab_id');
    }
}

