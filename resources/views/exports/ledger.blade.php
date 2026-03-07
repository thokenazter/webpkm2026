@php
    $period = $year . (isset($month) && $month ? ('-' . str_pad($month, 2, '0', STR_PAD_LEFT)) : '');
@endphp
<table>
    <tr>
        <td colspan="7"><strong>BKU (Buku Kas Umum) - Periode {{ $period }}</strong></td>
    </tr>
    <tr>
        <td colspan="7">Tanggal ekspor: {{ now()->format('d/m/Y H:i') }}</td>
    </tr>
    <tr></tr>
    <tr>
        <th>Tanggal</th>
        <th>Akun</th>
        <th>Uraian</th>
        <th>Referensi</th>
        <th>Debit</th>
        <th>Kredit</th>
        <th>Saldo</th>
    </tr>
    @foreach ($entries as $e)
        <tr>
            <td>{{ optional($e->entry_date)->format('d/m/Y') }}</td>
            <td>{{ $e->account_type }}</td>
            <td>{{ $e->description }}</td>
            <td>{{ $e->reference }}</td>
            <td>{{ number_format($e->debit, 2, ',', '.') }}</td>
            <td>{{ number_format($e->credit, 2, ',', '.') }}</td>
            <td>{{ number_format($e->balance, 2, ',', '.') }}</td>
        </tr>
    @endforeach
    <tr></tr>
    <tr>
        <td colspan="4"><strong>Jumlah</strong></td>
        <td><strong>{{ number_format($summary['debit'] ?? 0, 2, ',', '.') }}</strong></td>
        <td><strong>{{ number_format($summary['credit'] ?? 0, 2, ',', '.') }}</strong></td>
        <td></td>
    </tr>
</table>

