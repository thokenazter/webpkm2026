<x-app-layout>
    {{-- <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Saldo Pegawai') }}
            </h2>
            <div class="text-sm text-gray-600">
                {{ Carbon\Carbon::now()->format('d F Y') }}
            </div>
        </div>
    </x-slot> --}}

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <h1 class="text-2xl font-bold mb-2 flex items-center">
                                <i class="fas fa-wallet mr-3"></i>Saldo Pegawai BOK
                            </h1>
                            <p class="text-emerald-100">Laporan pembayaran dan saldo pegawai dari kegiatan LPJ</p>
                            <div class="mt-3 flex items-center text-sm text-emerald-200">
                                <i class="fas fa-users mr-2"></i>
                                <span>{{ $totalEmployees }} pegawai terdaftar</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-coins mr-2"></i>
                                <span>Total anggaran: Rp {{ number_format($totalSaldo, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="{{ route('employees.index') }}" class="bg-white text-emerald-600 px-6 py-2 rounded-lg font-semibold hover:bg-emerald-50 transition duration-200 shadow-md btn-modern">
                                <i class="fas fa-user-friends mr-2"></i>Kelola Pegawai
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-500 text-white rounded-xl">
                            <i class="fas fa-users text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Pegawai</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $totalEmployees }}</p>
                            <p class="text-xs text-gray-500">pegawai aktif</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 bg-green-500 text-white rounded-xl">
                            <i class="fas fa-money-bill-wave text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Anggaran</p>
                            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">keseluruhan saldo</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 bg-purple-500 text-white rounded-xl">
                            <i class="fas fa-calculator text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Rata-Rata Bayar</p>
                            <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($avgSaldo, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">per pegawai</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-8">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 bg-emerald-500 text-white rounded-xl">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900">Pencarian Pegawai</h3>
                            <p class="text-sm text-gray-600">Cari berdasarkan nama, NIP, atau pangkat</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('employee-saldo.index') }}" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Cari berdasarkan nama, NIP, atau pangkat..."
                                       class="pl-10 w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition duration-200">
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2 px-6 rounded-xl transition duration-200 shadow-md btn-modern">
                                <i class="fas fa-search mr-2"></i>Cari
                            </button>
                            @if(request('search'))
                                <a href="{{ route('employee-saldo.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-xl transition duration-200 shadow-md btn-modern">
                                    <i class="fas fa-times mr-2"></i>Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Employee Saldo Table -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 bg-indigo-500 text-white rounded-xl">
                                <i class="fas fa-table"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900">Daftar Saldo Pegawai</h3>
                                <p class="text-sm text-gray-600">Rincian pembayaran untuk setiap pegawai</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-indigo-600">{{ $employees->count() }}</div>
                            <div class="text-xs text-gray-500">
                                @if(request('search'))
                                    hasil pencarian
                                @else
                                    total pegawai
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pegawai</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pangkat/Golongan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah LPJ</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Transport</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Uang Harian</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Saldo</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($employees as $index => $employee)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            {{-- <div class="flex items-center justify-center w-10 h-10 bg-emerald-500 text-white rounded-lg text-sm font-bold mr-4">
                                                {{ $index + 1 }}
                                            </div> --}}
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $employee->nama }}</div>
                                                <div class="text-sm text-gray-500 flex items-center">
                                                    <i class="fas fa-id-card mr-1"></i>{{ $employee->nip }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $employee->pangkat_golongan }}</div>
                                        @if($employee->jabatan)
                                            <div class="text-xs text-gray-500">{{ $employee->jabatan }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 flex items-center w-fit">
                                            <i class="fas fa-file-alt mr-1"></i>{{ $employee->total_lpj_count_scoped }} LPJ
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-blue-600">
                                            Rp {{ number_format($employee->total_transport_scoped ?? 0, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500">transport</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-green-600">
                                            Rp {{ number_format($employee->total_per_diem_scoped ?? 0, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500">uang harian</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php $combined = (int) (($employee->total_saldo_scoped ?? 0) + ($employee->total_optional_scoped ?? 0)); @endphp
                                        <div class="font-bold text-lg text-purple-600">
                                            Rp {{ number_format($combined, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500">total bayar (incl. opsional)</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('employee-saldo.show', $employee) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-semibold rounded-lg transition duration-200 shadow-md btn-modern">
                                            <i class="fas fa-eye mr-2"></i>Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-user-slash text-6xl text-gray-300 mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                                @if(request('search'))
                                                    Pegawai Tidak Ditemukan
                                                @else
                                                    Belum Ada Data Pegawai
                                                @endif
                                            </h3>
                                            <p class="text-gray-500 text-center">
                                                @if(request('search'))
                                                    Tidak ada pegawai yang ditemukan dengan kata kunci "<strong>{{ request('search') }}</strong>". <br>
                                                    Coba gunakan kata kunci yang berbeda.
                                                @else
                                                    Belum ada data pegawai yang tersimpan dalam sistem. <br>
                                                    Silakan tambahkan pegawai terlebih dahulu.
                                                @endif
                                            </p>
                                            @if(request('search'))
                                                <a href="{{ route('employee-saldo.index') }}" class="mt-4 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                                                    <i class="fas fa-times mr-2"></i>Reset Pencarian
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($employees->count() > 0)
                            <tfoot class="bg-gradient-to-r from-emerald-50 to-teal-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-900">
                                        <i class="fas fa-calculator mr-2"></i>Total Keseluruhan:
                                    </td>
                                    <td class="px-6 py-4 font-bold text-blue-700">
                                        Rp {{ number_format($employees->sum('total_transport_scoped'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-green-700">
                                        Rp {{ number_format($employees->sum('total_per_diem_scoped'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-purple-700 text-xl">
                                        @php $sumCombined = $employees->sum(fn($e) => (int) (($e->total_saldo_scoped ?? 0) + ($e->total_optional_scoped ?? 0))); @endphp
                                        Rp {{ number_format($sumCombined, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4"></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>
