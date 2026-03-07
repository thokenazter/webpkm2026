<?php

namespace App\Http\Controllers;

use App\Modules\BOK\Models\LedgerEntry;
use Illuminate\Http\Request;

class LedgerPageController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeSuperAdmin($request);

        $year = (int) $request->query('year', (int) date('Y'));
        $month = $request->filled('month') ? (int) $request->query('month') : null;

        $q = LedgerEntry::query()->where('period_year', $year);
        if (!empty($month)) $q->where('period_month', $month);
        $entries = $q->orderBy('entry_date')->orderBy('id')->paginate(25)->appends($request->query());

        $sumDebit = (clone $q)->sum('debit');
        $sumCredit = (clone $q)->sum('credit');

        return view('bok.ledger', compact('year', 'month', 'entries', 'sumDebit', 'sumCredit'));
    }

    private function authorizeSuperAdmin(Request $request): void
    {
        $user = $request->user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Unauthorized. Super Admin access required.');
        }
    }
}

