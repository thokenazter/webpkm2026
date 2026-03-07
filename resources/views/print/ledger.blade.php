@php
    use App\Modules\BOK\Models\LedgerEntry;
    $q = LedgerEntry::query()->where('period_year', $year);
    if (!empty($month)) $q->where('period_month', (int)$month);
    $entries = $q->orderBy('entry_date')->orderBy('id')->get();
    $sumDebit = $entries->sum('debit');
    $sumCredit = $entries->sum('credit');
    $period = $year . (isset($month) && $month ? ('-' . str_pad($month, 2, '0', STR_PAD_LEFT)) : '');
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>BKU Periode {{ $period }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        .meta { margin-bottom: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #333; padding: 6px; }
        th { background: #f3f3f3; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .no-border { border: none; }
    </style>
    </head>
<body>
    <h1 class="text-center">Buku Kas Umum (BKU)</h1>
    <div class="meta">
        <div>Periode: <strong>{{ $period }}</strong></div>
        <div>Tanggal cetak: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%">Tanggal</th>
                <th style="width: 16%">No. Bukti</th>
                <th>Uraian</th>
                <th style="width: 16%" class="text-right">Penerimaan</th>
                <th style="width: 16%" class="text-right">Pengeluaran</th>
                <th style="width: 16%" class="text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entries as $e)
                <tr>
                    <td>{{ optional($e->entry_date)->format('d/m/Y') }}</td>
                    <td>{{ $e->reference }}</td>
                    <td>{{ $e->description }}</td>
                    <td class="text-right">{{ $e->debit ? number_format($e->debit, 2, ',', '.') : '-' }}</td>
                    <td class="text-right">{{ $e->credit ? number_format($e->credit, 2, ',', '.') : '-' }}</td>
                    <td class="text-right">{{ number_format($e->balance, 2, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td class="no-border" colspan="3"><strong>Jumlah</strong></td>
                <td class="text-right"><strong>{{ number_format($sumDebit, 2, ',', '.') }}</strong></td>
                <td class="text-right"><strong>{{ number_format($sumCredit, 2, ',', '.') }}</strong></td>
                <td class="no-border"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>

