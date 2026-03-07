<?php

namespace App\Services;

use App\Models\AnnualBudget;
use App\Models\BudgetAllocation;
use App\Models\Rab;

class BudgetAllocationService
{
    /**
     * Ensure there is a BudgetAllocation for the given RAB and year.
     * If missing, create it. Always sync allocated_amount with the RAB total.
     */
    public function ensureForRab(Rab $rab, ?int $year = null): BudgetAllocation
    {
        $year = $year ?? (int) date('Y');

        $budget = AnnualBudget::firstOrCreate(
            ['year' => $year, 'name' => 'Pagu BOK'],
            [
                'amount' => 0,
                'description' => 'Auto-created pagu for year ' . $year,
            ]
        );

        $allocation = BudgetAllocation::firstOrNew([
            'annual_budget_id' => $budget->id,
            'rab_id' => $rab->id,
        ]);
        $allocation->allocated_amount = (float) $rab->total;
        if (!$allocation->exists) {
            $allocation->notes = 'Auto allocation from RAB';
        }
        $allocation->save();

        return $allocation;
    }
}

