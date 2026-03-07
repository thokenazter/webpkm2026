<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white">
                    <h1 class="text-2xl font-bold">Rekonsiliasi {{ sprintf('%04d-%02d', $rec->year, $rec->month) }}</h1>
                    <p class="text-indigo-100">Status: {{ $rec->status }} • Matched: {{ $rec->matched_count }} • Unmatched: {{ $rec->unmatched_count }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Uraian</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Ledger Ref</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Ledger Uraian</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach (($rec->data['rows'] ?? []) as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $row['row']['date'] ?? '' }}</td>
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $row['row']['description'] ?? '' }}</td>
                            <td class="px-6 py-3 text-sm text-gray-900 text-right">{{ number_format((float)($row['row']['amount'] ?? 0), 2, ',', '.') }}</td>
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $row['ledger_ref'] ?? '-' }}</td>
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $row['ledger_desc'] ?? '-' }}</td>
                            <td class="px-6 py-3 text-sm">
                                @if(!empty($row['matched']))
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs font-medium">Matched</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-amber-100 text-amber-700 text-xs font-medium">Unmatched</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

