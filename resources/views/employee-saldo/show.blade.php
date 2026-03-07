<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-teal-600 to-cyan-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-user-circle text-2xl mr-3"></i>
                                <h1 class="text-2xl font-bold">Detail Saldo Pegawai</h1>
                            </div>
                            <div class="mb-3">
                                <div class="flex items-center text-lg">
                                    <span class="font-semibold mr-2">{{ $employee->nama }}</span>
                                    <span class="mx-2">•</span>
                                    <span class="text-teal-100">{{ $employee->pangkat_golongan }}</span>
                                </div>
                            </div>
                            <p class="text-teal-100 text-lg">{{ $employee->jabatan ?? 'Pegawai' }}</p>
                            <div class="mt-3 flex flex-wrap items-center text-sm text-teal-200">
                                <span class="flex items-center mr-4">
                                    <i class="fas fa-id-card mr-1"></i>
                                    NIP: {{ $employee->nip }}
                                </span>
                                <span class="flex items-center mr-4">
                                    <i class="fas fa-file-alt mr-1"></i>
                                    {{ $participations->count() }} LPJ
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-coins mr-1"></i>
                                    Total: Rp {{ number_format($totalSaldo ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 lg:mt-0 flex flex-wrap gap-3">
                            <a href="{{ route('employees.show', $employee) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition duration-200 shadow-md btn-modern">
                                <i class="fas fa-user mr-2"></i>Profil Pegawai
                            </a>
                            <a href="{{ route('employee-saldo.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200 shadow-md btn-modern">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Employee Information Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 bg-teal-500 text-white rounded-xl">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-bold text-gray-900">Informasi Pegawai</h3>
                                    <p class="text-sm text-gray-600">Data lengkap pegawai</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-user text-blue-500 mr-2"></i>Nama Lengkap
                                    </label>
                                    <p class="text-gray-900 font-medium">{{ $employee->nama }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-id-card text-green-500 mr-2"></i>NIP
                                    </label>
                                    <p class="text-gray-900 font-mono">{{ $employee->nip }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-award text-purple-500 mr-2"></i>Pangkat/Golongan
                                    </label>
                                    <p class="text-gray-900">{{ $employee->pangkat_golongan }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-briefcase text-orange-500 mr-2"></i>Jabatan
                                    </label>
                                    <p class="text-gray-900">{{ $employee->jabatan ?? 'Tidak ada data' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Participation History Table -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-10 h-10 bg-indigo-500 text-white rounded-xl">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-bold text-gray-900">Riwayat Partisipasi LPJ</h3>
                                        <p class="text-sm text-gray-600">Detail setiap kegiatan yang diikuti</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-indigo-600">{{ $participations->count() }}</div>
                                    <div class="text-xs text-gray-500">total kegiatan</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">LPJ</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kegiatan</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Transport</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Uang Harian</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($participations as $index => $participation)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex items-center justify-center w-8 h-8 bg-indigo-500 text-white rounded-lg text-sm font-bold mr-3">
                                                    {{ $index + 1 }}
                                                </div>
                                                <div>
                                                    <a href="{{ route('lpjs.show', $participation->lpj) }}" class="font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                                        {{ $participation->lpj->no_surat }}
                                                    </a>
                                                    <div class="text-xs mt-1">
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $participation->lpj->type == 'SPPT' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                            {{ $participation->lpj->type }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 font-medium max-w-xs truncate" title="{{ $participation->lpj->kegiatan }}">
                                                {{ $participation->lpj->kegiatan }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <i class="fas fa-calendar mr-1"></i>{{ $participation->lpj->tanggal_kegiatan }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-blue-600">
                                                Rp {{ number_format($participation->transport_amount, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($participation->per_diem_amount > 0)
                                                <div class="font-semibold text-green-600">
                                                    Rp {{ number_format($participation->per_diem_amount, 0, ',', '.') }}
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-sm">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-purple-600">
                                                Rp {{ number_format($participation->total_amount, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-500">
                                                {{ $participation->created_at->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $participation->created_at->format('H:i') }}
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Partisipasi</h3>
                                                <p class="text-gray-500 text-center">
                                                    Pegawai ini belum pernah berpartisipasi dalam kegiatan LPJ. <br>
                                                    Data akan muncul setelah pegawai ditambahkan ke dalam LPJ.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                @if($participations->count() > 0)
                                    <tfoot class="bg-gradient-to-r from-teal-50 to-cyan-50">
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-right font-bold text-gray-900">
                                                <i class="fas fa-calculator mr-2"></i>Total Keseluruhan:
                                            </td>
                                            <td class="px-6 py-4 font-bold text-blue-700">
                                                Rp {{ number_format($totalTransport ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 font-bold text-green-700">
                                                Rp {{ number_format($totalPerDiem ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 font-bold text-purple-700 text-xl">
                                                Rp {{ number_format($totalSaldo ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4"></td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Sidebar -->
                <div class="space-y-6">
                    <!-- Summary Stats Cards -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 bg-teal-600 text-white rounded-xl">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-bold text-gray-900">Ringkasan Saldo</h3>
                                    <p class="text-sm text-gray-600">Total pembayaran pegawai</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <i class="fas fa-coins text-green-600 mr-2"></i>
                                        <span class="text-sm font-medium text-green-800">Total Saldo</span>
                                    </div>
                                </div>
                                <div class="text-2xl font-bold text-green-600">
                                    Rp {{ number_format($totalSaldo ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Optional Items (Opsional) -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                        <div class="bg-gradient-to-r from-amber-50 to-amber-100 px-6 py-4 border-b border-amber-200 rounded-t-2xl flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 bg-amber-500 text-white rounded-xl">
                                    <i class="fas fa-cookie-bite"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-bold text-gray-900">Item Opsional (Snack/Konsumsi/dll)</h3>
                                    <p class="text-sm text-gray-600">Kredit dari kegiatan sebagai penanggung jawab</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-gray-500">Total Item Opsional</div>
                                <div class="text-xl font-bold text-amber-600">Rp {{ number_format($totalOptional ?? 0, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="p-6">
                            @if(($optionalEntries ?? collect())->isEmpty())
                                <div class="text-center text-gray-500 py-6">Belum ada kredit item opsional.</div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full table-auto">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Periode</th>
                                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kegiatan</th>
                                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Item</th>
                                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @php $monthsName=[1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des']; @endphp
                                            @foreach($optionalEntries as $e)
                                                <tr class="hover:bg-amber-50">
                                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $monthsName[(int) $e->month] ?? $e->month }} {{ $e->year }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $e->poa?->kegiatan ?? '-' }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $e->label }} <span class="text-xs text-gray-500">({{ strtoupper($e->category ?? 'opsional') }})</span></td>
                                                    <td class="px-4 py-2 text-sm text-gray-900 text-right">Rp {{ number_format((float) $e->amount, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions Card -->
                    {{-- <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-xl">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-bold text-gray-900">Aksi Cepat</h3>
                                    <p class="text-sm text-gray-600">Tindakan yang tersedia</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('employees.show', $employee) }}" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold transition duration-200 shadow-md btn-modern flex items-center justify-center">
                                <i class="fas fa-user mr-2"></i>Lihat Profil Pegawai
                            </a>
                            
                            <a href="{{ route('employees.edit', $employee) }}" class="w-full bg-amber-500 hover:bg-amber-600 text-white px-4 py-3 rounded-lg font-semibold transition duration-200 shadow-md btn-modern flex items-center justify-center">
                                <i class="fas fa-edit mr-2"></i>Edit Data Pegawai
                            </a>
                            
                            <a href="{{ route('lpjs.create') }}" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg font-semibold transition duration-200 shadow-md btn-modern flex items-center justify-center">
                                <i class="fas fa-plus mr-2"></i>Buat LPJ Baru
                            </a>
                            
                            <a href="{{ route('employee-saldo.index') }}" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-3 rounded-lg font-semibold transition duration-200 shadow-md btn-modern flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                            </a>
                        </div>
                    </div> --}}

                    <!-- Employee Stats Card -->
                    {{-- <div class="bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl shadow-lg text-white p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex items-center justify-center w-10 h-10 bg-white/20 rounded-xl">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold">Info Pegawai</h3>
                                <p class="text-teal-100 text-sm">Data ringkas pegawai</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-teal-100">NIP:</span>
                                <span class="font-bold font-mono">{{ $employee->nip }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-teal-100">Pangkat:</span>
                                <span class="font-bold">{{ $employee->pangkat_golongan }}</span>
                            </div>
                            @if($employee->jabatan)
                            <div class="flex justify-between items-center">
                                <span class="text-teal-100">Jabatan:</span>
                                <span class="font-bold">{{ $employee->jabatan }}</span>
                            </div>
                            @endif
                            <div class="border-t border-teal-400 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold">Total Kegiatan:</span>
                                    <span class="text-2xl font-bold">{{ $participations->count() }} LPJ</span>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>
