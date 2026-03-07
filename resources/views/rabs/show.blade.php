<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold mb-1">Detail RAB</h1>
                            <p class="text-indigo-100">Rincian komponen, item dan total anggaran</p>
                        </div>
                        <div class="flex gap-2">
                            @if(auth()->user()?->isSuperAdmin())
                                <a href="{{ route('rabs.export-all-templated') }}" class="inline-flex items-center bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium shadow-sm"><i class="fas fa-file-excel mr-2"></i>Export All</a>
                                <a href="{{ route('rabs.edit', $rab) }}" class="inline-flex items-center bg-yellow-400 hover:bg-yellow-500 text-gray-900 px-4 py-2 rounded-lg font-medium shadow-sm"><i class="fas fa-edit mr-2"></i>Edit</a>
                                <a href="{{ route('rabs.export', $rab) }}" class="inline-flex items-center bg-white text-indigo-700 px-4 py-2 rounded-lg font-medium hover:bg-indigo-50 shadow-sm"><i class="fas fa-file-excel mr-2"></i>Export</a>
                            @endif
                            <a href="{{ route('rabs.index') }}" class="inline-flex items-center bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg font-medium"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-6">
                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4">{{ session('error') }}</div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <div class="text-xs text-gray-500">Komponen</div>
                            @php
                                $componentBadges = [
                                    'Peningkatan Layanan Kesehatan Sesuai Siklus Hidup' => 'bg-pink-100 text-pink-800 border-pink-200',
                                    'Surveilans, respons penyakit dan kesehatan lingkungan' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                    'Pemberian Makanan Tambahan (PMT) berbahan pangan lokal' => 'bg-orange-100 text-orange-800 border-orange-200',
                                    'MANAGEMEN PUSKESMAS' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                    'INSENTIF UKM' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                ];
                                $badgeClass = $componentBadges[$rab->komponen] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                            @endphp
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                {{ $rab->komponen }}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Rincian Menu</div>
                            @php
                                $componentBadges = [
                                    'Peningkatan Layanan Kesehatan Sesuai Siklus Hidup' => 'bg-pink-100 text-pink-800 border-pink-200',
                                    'Surveilans, respons penyakit dan kesehatan lingkungan' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                    'Pemberian Makanan Tambahan (PMT) berbahan pangan lokal' => 'bg-orange-100 text-orange-800 border-orange-200',
                                    'MANAGEMEN PUSKESMAS' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                    'INSENTIF UKM' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                ];
                                $badgeClass = $componentBadges[$rab->komponen] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                            @endphp
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                {{ $rab->rincian_menu }}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Kegiatan</div>
                            @php
                                $componentBadges = [
                                    'Peningkatan Layanan Kesehatan Sesuai Siklus Hidup' => 'bg-pink-100 text-pink-800 border-pink-200',
                                    'Surveilans, respons penyakit dan kesehatan lingkungan' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                    'Pemberian Makanan Tambahan (PMT) berbahan pangan lokal' => 'bg-orange-100 text-orange-800 border-orange-200',
                                    'MANAGEMEN PUSKESMAS' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                    'INSENTIF UKM' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                ];
                                $badgeClass = $componentBadges[$rab->komponen] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                            @endphp
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                {{ $rab->kegiatan }}
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Item</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Faktor</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga Satuan</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($rab->items as $i => $item)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $i + 1 }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->label }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            @php
                                                $parts = collect($item->factors ?? [])->map(fn($f) => (int)($f['value'] ?? 0) . ' ' . ($f['label'] ?? $f['key'] ?? '-'));
                                            @endphp
                                            {{ $parts->join(' × ') }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-900">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-gray-50">
                                    <td colspan="4" class="px-4 py-3 text-right font-semibold">Jumlah Total</td>
                                    <td class="px-4 py-3 text-sm font-bold text-gray-900">Rp {{ number_format($rab->total, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
