<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnnualBudget extends Model
{
    protected $fillable = [
        'year',
        'name',
        'amount',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'year' => 'integer',
    ];

    public function allocations(): HasMany
    {
        return $this->hasMany(BudgetAllocation::class);
    }
}

