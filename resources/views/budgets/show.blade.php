<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="mb-2">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold mb-1">Detail Pagu Tahunan</h1>
                        <p class="text-indigo-100">Ringkasan Pagu dan Alokasi Kegiatan</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('budgets.edit', $budget) }}" class="inline-flex items-center bg-yellow-400 hover:bg-yellow-500 text-gray-900 px-4 py-2 rounded-lg font-medium shadow-sm"><i class="fas fa-edit mr-2"></i>Edit</a>
                        <a href="{{ route('allocations.create') }}" class="inline-flex items-center bg-white text-indigo-700 px-4 py-2 rounded-lg font-medium hover:bg-indigo-50 shadow-sm"><i class="fas fa-plus mr-2"></i>Tambah Alokasi</a>
                        <a href="{{ route('budgets.index') }}" class="inline-flex items-center bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg font-medium"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <div class="text-xs text-gray-500">Tahun</div>
                        <div class="text-lg font-semibold">{{ $budget->year }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Nama</div>
                        <div class="text-lg font-semibold">{{ $budget->name }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Pagu</div>
                        <div class="text-lg font-semibold">Rp {{ number_format($budget->amount, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Alokasi Kegiatan</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">RAB/Kegiatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Alokasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Realisasi (LPJ)</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sisa</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($budget->allocations as $a)
                                    @php
                                        $realized = \App\Models\Lpj::where('kegiatan', $a->rab->kegiatan)
                                            ->whereYear('created_at', $budget->year)
                                            ->with('participants')->get()
                                            ->sum(fn($lpj) => $lpj->participants->sum('total_amount'));
                                        $remaining = (float) $a->allocated_amount - (float) $realized;
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-3 text-sm text-gray-900">
                                            <div class="font-semibold">{{ $a->rab->kegiatan }}</div>
                                            <div class="text-xs text-gray-500">{{ $a->rab->rincian_menu }} · {{ $a->rab->komponen }}</div>
                                        </td>
                                        <td class="px-6 py-3 text-sm text-gray-900">Rp {{ number_format($a->allocated_amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-900">Rp {{ number_format($realized, 0, ',', '.') }}</td>
                                        <td class="px-6 py-3 text-sm font-semibold {{ $remaining < 0 ? 'text-red-600' : 'text-green-700' }}">Rp {{ number_format($remaining, 0, ',', '.') }}</td>
                                        <td class="px-6 py-3 text-sm">
                                            <a href="{{ route('allocations.show', $a) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-eye mr-1"></i>Lihat</a>
                                            <a href="{{ route('allocations.edit', $a) }}" class="inline-flex items-center text-yellow-600 hover:text-yellow-900 mr-3"><i class="fas fa-edit mr-1"></i>Edit</a>
                                            <form action="{{ route('allocations.destroy', $a) }}" method="POST" class="inline" onsubmit="return confirm('Yakin?')">
                                                @csrf @method('DELETE')
                                                <button class="inline-flex items-center text-red-600 hover:text-red-900"><i class="fas fa-trash mr-1"></i>Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
