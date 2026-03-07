<table>
    <tr>
        <td colspan="7" style="font-weight: bold; font-size: 14px; text-align: center; background-color: #E3F2FD; padding: 10px;">
            {{ $componentName }}
        </td>
    </tr>
    <tr><td colspan="7"></td></tr>
    <tr>
        <td colspan="7" style="font-size: 11px; color: #666;">
            Total RAB: {{ $totalRabs }} | Total Budget: Rp {{ number_format($totalBudget, 0, ',', '.') }}
        </td>
    </tr>
    <tr><td colspan="7"></td></tr>

    <tr style="background-color: #E3F2FD; font-weight: bold;">
        <td style="text-align: center; padding: 6px;">No</td>
        <td style="padding: 6px;">Rincian Menu</td>
        <td style="padding: 6px;">Kegiatan</td>
        <td style="padding: 6px;">Item</td>
        <td style="padding: 6px;">Faktor Perkalian</td>
        <td style="text-align: right; padding: 6px;">Harga Satuan (Rp)</td>
        <td style="text-align: right; padding: 6px;">Sub Total (Rp)</td>
    </tr>

    @php
        $itemCounter = 1;
    @endphp

    @foreach ($groupedData as $menuName => $menuGroup)
        @php
            // Calculate menuRowSpan manually
            $menuRowSpan = 0;
            foreach ($menuGroup as $kgName => $kgRabs) {
                foreach ($kgRabs as $rab) {
                    $menuRowSpan += $rab->items->count();
                }
            }
            $firstMenu = true;
        @endphp

        @foreach ($menuGroup as $kegiatanName => $kegiatanRabs)
            @php
                // Calculate kegiatanRowSpan manually
                $kegiatanRowSpan = 0;
                foreach ($kegiatanRabs as $rab) {
                    $kegiatanRowSpan += $rab->items->count();
                }
                $firstKegiatan = true;
            @endphp

            @foreach ($kegiatanRabs as $rab)
                @foreach ($rab->items as $item)
                <tr>
                    @if ($firstMenu && $firstKegiatan)
                        <td style="text-align: center; padding: 4px; vertical-align: top;" rowspan="{{ $menuRowSpan }}">{{ $itemCounter }}</td>
                        <td style="padding: 4px; vertical-align: top;" rowspan="{{ $menuRowSpan }}">{{ $menuName }}</td>
                        <td style="padding: 4px; vertical-align: top;" rowspan="{{ $kegiatanRowSpan }}">{{ $kegiatanName }}</td>
                        @php
                            $firstMenu = false;
                            $firstKegiatan = false;
                        @endphp
                    @endif

                    <td style="padding: 4px;">{{ $item->label }}</td>

                    <td style="padding: 4px; font-size: 11px;">
                        @php
                            $factors = collect($item->factors ?? [])->map(function($f) {
                                $label = $f['label'] ?? $f['key'] ?? '-';
                                $value = (float)($f['value'] ?? 0);
                                return $label . ' x ' . $value;
                            })->join(' × ');
                        @endphp
                        {{ $factors ?: '-' }}
                    </td>

                    <td style="text-align: right; padding: 4px;">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td style="text-align: right; padding: 4px;">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>

                @php
                    $itemCounter++;
                @endphp
                @endforeach
            @endforeach
        @endforeach
    @endforeach

    <tr style="font-weight: bold; background-color: #FFF3E0;">
        <td colspan="6" style="text-align: right; padding: 8px;">TOTAL {{ $componentName }}</td>
        <td style="text-align: right; padding: 8px;">{{ number_format($totalBudget, 0, ',', '.') }}</td>
    </tr>

    <tr><td colspan="7"></td></tr>
</table>