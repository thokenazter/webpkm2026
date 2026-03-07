@php
    $rows = $rab->items;
@endphp
<table>
    <tr>
        <td colspan="5" style="font-weight: bold; font-size: 14px;">Rencana Anggaran Biaya (RAB) BOK Puskesmas</td>
    </tr>
    <tr><td colspan="5"></td></tr>
    <tr>
        <td style="font-weight: bold;">Komponen</td>
        <td colspan="4">{{ $rab->komponen }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Rincian Menu</td>
        <td colspan="4">{{ $rab->rincian_menu }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Kegiatan</td>
        <td colspan="4">{{ $rab->kegiatan }}</td>
    </tr>
    <tr><td colspan="5"></td></tr>

    <tr>
        <th>No</th>
        <th>Item</th>
        <th>Faktor Perkalian</th>
        <th>Harga Satuan (Rp)</th>
        <th>Sub Total (Rp)</th>
    </tr>
    @foreach ($rows as $i => $item)
        @php
            $f = collect($item->factors ?? [])->map(fn($x) => ($x['label'] ?? $x['key'] ?? '-') . ' x ' . (float)($x['value'] ?? 0))->join(' × ');
        @endphp
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->label }}</td>
            <td>{{ $f }}</td>
            <td>{{ number_format($item->unit_price, 0, ',', '.') }}</td>
            <td>{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="4" style="font-weight:bold;">Jumlah Total</td>
        <td style="font-weight:bold;">{{ number_format($rab->total, 0, ',', '.') }}</td>
    </tr>
</table>

