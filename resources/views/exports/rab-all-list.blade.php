<table>
    <tr>
        <td colspan="7" style="font-weight: bold; font-size: 14px; text-align: center; background-color: #E3F2FD; padding: 10px;">
            COMPLETE LIST OF ALL RABs
        </td>
    </tr>
    <tr><td colspan="7"></td></tr>
    <tr style="background-color: #E3F2FD; font-weight: bold;">
        <td style="text-align: center; padding: 6px;">No</td>
        <td style="padding: 6px;">Komponen</td>
        <td style="padding: 6px;">Rincian Menu</td>
        <td style="padding: 6px;">Kegiatan</td>
        <td style="text-align: center; padding: 6px;">Total Items</td>
        <td style="text-align: right; padding: 6px;">Total Budget (Rp)</td>
        <td style="text-align: center; padding: 6px;">Created Date</td>
    </tr>

    @foreach ($rabs as $index => $rab)
        <tr>
            <td style="text-align: center; padding: 4px;">{{ $index + 1 }}</td>
            <td style="padding: 4px;">{{ $rab->komponen }}</td>
            <td style="padding: 4px;">{{ $rab->rincian_menu }}</td>
            <td style="padding: 4px;">{{ $rab->kegiatan }}</td>
            <td style="text-align: center; padding: 4px;">{{ $rab->items->count() }}</td>
            <td style="text-align: right; padding: 4px;">{{ number_format($rab->total, 0, ',', '.') }}</td>
            <td style="text-align: center; padding: 4px;">{{ $rab->created_at->format('d/m/Y') }}</td>
        </tr>
    @endforeach

    <tr style="font-weight: bold; background-color: #FFF3E0;">
        <td colspan="4" style="text-align: right; padding: 8px;">GRAND TOTAL ALL RABs</td>
        <td style="text-align: center; padding: 8px;">{{ $rabs->sum(function($r) { return $r->items->count(); }) }}</td>
        <td style="text-align: right; padding: 8px;">{{ number_format($rabs->sum('total'), 0, ',', '.') }}</td>
        <td style="text-align: center; padding: 8px;">-</td>
    </tr>

    <tr><td colspan="7"></td></tr>
    <tr>
        <td colspan="7" style="font-size: 11px; color: #888; font-style: italic;">
            * Total RABs: {{ $rabs->count() }} items
        </td>
    </tr>
</table>