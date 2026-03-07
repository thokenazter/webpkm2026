<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnnualBudget;
use Illuminate\Support\Carbon;

class AnnualBudgetSeeder extends Seeder
{
    public function run(): void
    {
        $year = (int) (Carbon::now()->year);
        AnnualBudget::firstOrCreate(
            ['year' => $year, 'name' => 'Pagu BOK'],
            [
                'amount' => 500_000_000, // contoh pagu
                'description' => 'Pagu BOK tahun ' . $year,
            ]
        );
    }
}

