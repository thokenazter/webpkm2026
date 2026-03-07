<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Modules\BOK\Models\LedgerEntry;

class LedgerDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static string $view = 'filament.pages.ledger-dashboard';

    protected static ?string $navigationGroup = 'BOK Treasurer';

    protected static ?int $navigationSort = 10;

    public static function getNavigationLabel(): string
    {
        return 'Ledger (BKU)';
    }

    public function getEntries(int $year, ?int $month = null)
    {
        $q = LedgerEntry::query()->where('period_year', $year);
        if (!empty($month)) $q->where('period_month', $month);
        return $q->orderBy('entry_date')->orderBy('id')->limit(500)->get();
    }
}

