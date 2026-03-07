<?php

namespace App\Modules\BOK\Http\Controllers;

use App\Modules\BOK\Models\LedgerEntry;
use App\Modules\BOK\Models\ReportMonthly;
use Illuminate\Http\Request;

class LedgerOpeningController
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'year' => ['required','integer','min:2000','max:2100'],
            'month' => ['required','integer','min:1','max:12'],
            'amount' => ['required','numeric','min:0'],
            'account_type' => ['nullable','in:BANK,CASH'],
        ]);

        $year = (int) $data['year'];
        $month = (int) $data['month'];
        $amount = (float) $data['amount'];
        $account = strtoupper((string) ($data['account_type'] ?? config('bok.ledger.lpj_default_account', 'BANK')));
        $account = $account === 'CASH' ? 'CASH' : 'BANK';

        // Jangan izinkan perubahan saat periode dikunci
        $locked = ReportMonthly::query()
            ->where('year', $year)
            ->where('month', $month)
            ->where('status', 'LOCKED')
            ->exists();
        if ($locked) {
            return back()->with('error', 'Periode terkunci. Unlock periode terlebih dahulu.');
        }

        $ref = sprintf('OPENING-%04d-%02d', $year, $month);
        $date = sprintf('%04d-%02d-01', $year, $month);

        $opening = LedgerEntry::query()
            ->where('period_year', $year)
            ->where('period_month', $month)
            ->where('reference', $ref)
            ->first();

        if ($opening) {
            $opening->entry_date = $date;
            $opening->account_type = $account;
            $opening->description = 'Saldo Awal';
            $opening->debit = 0;
            $opening->credit = 0;
            $opening->balance = $amount;
            $opening->posted_at = now();
            $opening->save();
        } else {
            $opening = LedgerEntry::create([
                'entry_date'   => $date,
                'account_type' => $account,
                'description'  => 'Saldo Awal',
                'reference'    => $ref,
                'debit'        => 0,
                'credit'       => 0,
                'balance'      => $amount,
                'period_year'  => $year,
                'period_month' => $month,
                'posted_at'    => now(),
            ]);
        }

        // Recalculate running balance for the whole period based on opening
        $this->recalculatePeriod($year, $month, $opening);

        return back()->with('success', 'Saldo awal diperbarui.');
    }

    private function recalculatePeriod(int $year, int $month, LedgerEntry $opening): void
    {
        $startBalance = (float) $opening->balance;
        $prev = $startBalance;
        $entries = LedgerEntry::query()
            ->where('period_year', $year)
            ->where('period_month', $month)
            ->orderBy('entry_date')
            ->orderBy('id')
            ->get();

        foreach ($entries as $row) {
            if ($row->id === $opening->id) {
                if ((float) $row->balance !== $startBalance) {
                    $row->balance = $startBalance;
                    $row->save();
                }
                continue;
            }
            $prev = $prev + (float)$row->debit - (float)$row->credit;
            if ((float) $row->balance !== $prev) {
                $row->balance = $prev;
                $row->save();
            }
        }
    }
}

