<table>
    <tr>
        <td colspan="5" style="font-weight: bold; font-size: 16px; text-align: center; background-color: #E3F2FD; padding: 10px;">
            MASTER DASHBOARD RAB BOK PUSKESMAS
        </td>
    </tr>
    <tr><td colspan="5"></td></tr>
    <tr>
        <td colspan="5" style="font-size: 12px; color: #666;">
            Export Date: {{ $exportDate }}
        </td>
    </tr>
    <tr><td colspan="5"></td></tr>

    <tr style="background-color: #E3F2FD; font-weight: bold;">
        <td style="text-align: center; padding: 8px;">No</td>
        <td style="padding: 8px;">Komponen</td>
        <td style="text-align: center; padding: 8px;">Jumlah RAB</td>
        <td style="text-align: right; padding: 8px;">Total Anggaran (Rp)</td>
        <td style="text-align: right; padding: 8px;">Rata-rata (Rp)</td>
    </tr>

    @foreach ($summaryData as $index => $data)
        <tr>
            <td style="text-align: center; padding: 6px;">{{ $index + 1 }}</td>
            <td style="padding: 6px;">{{ $data['komponen'] }}</td>
            <td style="text-align: center; padding: 6px;">{{ $data['count'] }}</td>
            <td style="text-align: right; padding: 6px;">{{ number_format($data['total'], 0, ',', '.') }}</td>
            <td style="text-align: right; padding: 6px;">{{ number_format($data['avg_total'], 0, ',', '.') }}</td>
        </tr>
    @endforeach

    <tr style="font-weight: bold; background-color: #FFF3E0;">
        <td colspan="2" style="text-align: right; padding: 8px;">TOTAL</td>
        <td style="text-align: center; padding: 8px;">{{ $totalRabs }}</td>
        <td style="text-align: right; padding: 8px;">{{ number_format($totalBudget, 0, ',', '.') }}</td>
        <td style="text-align: right; padding: 8px;">{{ number_format($totalRabs > 0 ? $totalBudget / $totalRabs : 0, 0, ',', '.') }}</td>
    </tr>

    <tr><td colspan="5"></td></tr>
    <tr>
        <td colspan="5" style="font-size: 11px; color: #888; font-style: italic;">
            * Sheet berikutnya berisi detail RAB per komponen
        </td>
    </tr>
</table>