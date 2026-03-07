<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-start md:items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold mb-1">Buku Kas Umum (BKU)</h1>
                            <p class="text-indigo-100">Ringkasan transaksi kas BOK per periode</p>
                        </div>
                        <div class="mt-4 md:mt-0 flex gap-2">
                            <a href="{{ route('bok.ledger.export', ['year' => $year, 'month' => $month]) }}" class="inline-flex items-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-file-excel mr-2"></i>Export Excel
                            </a>
                            <a href="{{ route('bok.ledger.export-pdf', ['year' => $year, 'month' => $month]) }}" class="inline-flex items-center bg-white text-indigo-700 px-4 py-2 rounded-lg font-medium hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-file-pdf mr-2"></i>Export PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl">{{ session('success') }}</div>
            @endif

            <!-- Filter & Saldo Awal & Lock Periode -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 mb-8 p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                        <input type="number" name="year" min="2000" max="2100" value="{{ $year }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bulan (opsional)</label>
                        @php
                            $monthNames = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                        @endphp
                        <select name="month" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Bulan</option>
                            @foreach($monthNames as $i=>$name)
                                <option value="{{ $i }}" {{ (int)($month ?? 0) === (int)$i ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2 flex gap-3">
                        <button class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-filter mr-2"></i>Terapkan
                        </button>
                    </div>
                </form>

                <form method="POST" action="{{ route('bok.ledger.opening.store') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end mb-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                        <input type="number" name="year" min="2000" max="2100" value="{{ $year }}" class="mt-1 block w-full rounded-xl border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                        <input type="number" name="month" min="1" max="12" value="{{ $month ?? '' }}" class="mt-1 block w-full rounded-xl border-gray-300" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Akun</label>
                        <select name="account_type" class="mt-1 block w-full rounded-xl border-gray-300">
                            <option value="BANK">BANK</option>
                            <option value="CASH">CASH</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Saldo Awal</label>
                        <input type="number" step="0.01" name="amount" class="mt-1 block w-full rounded-xl border-gray-300" placeholder="0.00" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">&nbsp;</label>
                        <button class="inline-flex items-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md w-full">
                            <i class="fas fa-floppy-disk mr-2"></i>Simpan Saldo Awal
                        </button>
                    </div>
                </form>

                <form method="POST" action="{{ route('bok.ledger.period.toggle') }}" class="text-right">
                    @csrf
                    <input type="hidden" name="year" value="{{ $year }}">
                    <input type="hidden" name="month" value="{{ $month ?? '' }}">
                    <button class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md" onclick="return confirm('Ubah status lock periode?')">
                        <i class="fas fa-lock mr-2"></i>Lock/Unlock Periode
                    </button>
                </form>
            </div>

            <!-- Ringkasan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white shadow rounded-2xl p-4 border border-gray-100">
                    <div class="text-sm text-gray-500">Total Penerimaan</div>
                    <div class="text-2xl font-bold text-emerald-600">Rp {{ number_format($sumDebit, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white shadow rounded-2xl p-4 border border-gray-100">
                    <div class="text-sm text-gray-500">Total Pengeluaran</div>
                    <div class="text-2xl font-bold text-rose-600">Rp {{ number_format($sumCredit, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white shadow rounded-2xl p-4 border border-gray-100">
                    <div class="text-sm text-gray-500">Saldo Akhir (baris terakhir)</div>
                    <div class="text-2xl font-bold text-indigo-700">
                        @php $last = optional($entries->last())->balance; @endphp
                        Rp {{ number_format($last ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Tabel Ledger -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50 text-left">
                                <th class="px-6 py-3 text-xs font-semibold text-gray-600">Tanggal</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-600">Akun</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-600">Uraian</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-600">Referensi</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-600 text-right">Penerimaan</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-600 text-right">Pengeluaran</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-600 text-right">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($entries as $e)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm text-gray-900">{{ optional($e->entry_date)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-900">{{ $e->account_type }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-900">{{ $e->description }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-900">{{ $e->reference }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-900 text-right">{{ $e->debit ? number_format($e->debit, 0, ',', '.') : '-' }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-900 text-right">{{ $e->credit ? number_format($e->credit, 0, ',', '.') : '-' }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-900 text-right">{{ number_format($e->balance, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-6 text-center text-gray-500">Belum ada transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $entries->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
