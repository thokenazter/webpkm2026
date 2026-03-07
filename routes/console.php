<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\BudgetAllocationService;
use App\Models\Rab;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sync all RABs into Budget Allocations for given year (default: current year)
Artisan::command('allocations:sync {year?}', function (?string $year = null) {
    $y = (int) ($year ?: date('Y'));
    $svc = app(BudgetAllocationService::class);
    $bar = $this->output->createProgressBar();
    $bar->start();
    $count = 0;
    Rab::orderBy('id')->chunkById(200, function ($batch) use ($svc, $y, $bar, &$count) {
        foreach ($batch as $rab) {
            $svc->ensureForRab($rab, $y);
            $bar->advance();
            $count++;
        }
    });
    $bar->finish();
    $this->newLine();
    $this->info("Synced {$count} RAB(s) into allocations for year {$y}.");
})->purpose('Sync all RABs into Budget Allocations for the specified year');
