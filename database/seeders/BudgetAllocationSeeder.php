<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnnualBudget;
use App\Models\BudgetAllocation;
use App\Models\Rab;
use Illuminate\Support\Carbon;

class BudgetAllocationSeeder extends Seeder
{
    public function run(): void
    {
        $year = (int) (Carbon::now()->year);
        $budget = AnnualBudget::firstOrCreate(
            ['year' => $year, 'name' => 'Pagu BOK'],
            [
                'amount' => 500_000_000,
                'description' => 'Pagu BOK tahun ' . $year,
            ]
        );

        // Ambil beberapa RAB yang ada untuk dialokasikan
        $rabs = Rab::orderBy('id')->take(5)->get();
        foreach ($rabs as $rab) {
            BudgetAllocation::firstOrCreate(
                [
                    'annual_budget_id' => $budget->id,
                    'rab_id' => $rab->id,
                ],
                [
                    'allocated_amount' => (float) $rab->total,
                    'notes' => 'Alokasi awal otomatis dari seeder',
                ]
            );
        }
    }
}

