<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-teal-600 to-cyan-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <h1 class="text-2xl font-bold mb-2">Jadwal Kegiatan Bulanan</h1>
                            <p class="text-teal-100">Kelola tanggal kegiatan dan nomor surat secara otomatis</p>
                        </div>
                        <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                            @if($hasDraft)
                            <form action="{{ route('jadwal.finalize') }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin mengunci semua jadwal draft? Jadwal yang sudah dikunci tidak dapat diubah.')">
                                @csrf
                                <input type="hidden" name="month" value="{{ $month }}">
                                <input type="hidden" name="year" value="{{ $year }}">
                                <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-600 transition duration-200 shadow-md">
                                    <i class="fas fa-lock mr-2"></i>Finalisasi Bulan Ini
                                </button>
                            </form>
                            @endif
                            @if($hasFinalized)
                            <form action="{{ route('jadwal.unlock') }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membuka kunci jadwal? Jadwal akan kembali menjadi draft dan bisa digenerate ulang.')">
                                @csrf
                                <input type="hidden" name="month" value="{{ $month }}">
                                <input type="hidden" name="year" value="{{ $year }}">
                                <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition duration-200 shadow-md">
                                    <i class="fas fa-unlock mr-2"></i>Buka Kunci
                                </button>
                            </form>
                            @endif
                            <button onclick="document.getElementById('generateModal').classList.remove('hidden')" class="bg-white text-teal-600 px-6 py-2 rounded-lg font-semibold hover:bg-teal-50 transition duration-200 shadow-md">
                                <i class="fas fa-magic mr-2"></i>Generate Jadwal
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-2xl mb-6 flex items-center shadow-lg">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-2xl mb-6 flex items-center shadow-lg">
                    <i class="fas fa-exclamation-triangle mr-3 text-red-500"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Conflicts Warning -->
            @if($conflicts->isNotEmpty())
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-6 py-4 rounded-2xl mb-6 shadow-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle mr-3 text-yellow-600 mt-1"></i>
                        <div>
                            <strong class="block mb-2">Peringatan: Ditemukan Bentrok Jadwal!</strong>
                            <ul class="list-disc list-inside text-sm">
                                @foreach($conflicts->take(5) as $conflict)
                                    <li>{{ $conflict['employee'] }} bentrok pada tanggal {{ \Carbon\Carbon::parse($conflict['date'])->translatedFormat('d F Y') }}</li>
                                @endforeach
                                @if($conflicts->count() > 5)
                                    <li>... dan {{ $conflicts->count() - 5 }} bentrok lainnya</li>
                                @endif
                            </ul>
                            <p class="mt-2 text-sm">Silakan sesuaikan tanggal sebelum melakukan finalisasi.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Month/Year Filter -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
                <form method="GET" action="{{ route('jadwal.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select name="month" class="border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select name="year" class="border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500">
                            @for($y = 2024; $y <= 2027; $y++)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </form>
            </div>

            <!-- Schedules Table -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Jadwal {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}
                    </h3>
                    <span class="text-sm text-gray-500">{{ $schedules->count() }} kegiatan</span>
                </div>
                
                @if($schedules->isEmpty())
                    <div class="p-12 text-center">
                        <i class="fas fa-calendar-times text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 mb-4">Belum ada jadwal untuk bulan ini.</p>
                        <button onclick="document.getElementById('generateModal').classList.remove('hidden')" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition">
                            <i class="fas fa-magic mr-2"></i>Generate Jadwal Sekarang
                        </button>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Surat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($schedules as $schedule)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-blue-100 text-blue-800">
                                                {{ $schedule->nomor_surat ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($schedule->type === 'SPPT')
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800" title="Darat - {{ $schedule->desa_count }} Desa">
                                                    <i class="fas fa-road mr-1"></i>SPPT
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800" title="Seberang - {{ $schedule->desa_count }} Desa">
                                                    <i class="fas fa-ship mr-1"></i>SPPD
                                                </span>
                                            @endif
                                            <span class="text-xs text-gray-400 ml-1">({{ $schedule->desa_count }} desa)</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $schedule->poa->kegiatan ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ $schedule->poa->rab->rincian_menu ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($schedule->poa->participants->take(3) as $p)
                                                    <span class="inline-block px-2 py-0.5 text-xs bg-gray-100 rounded">{{ $p->employee->nama ?? 'N/A' }}</span>
                                                @endforeach
                                                @if($schedule->poa->participants->count() > 3)
                                                    <span class="inline-block px-2 py-0.5 text-xs bg-gray-200 rounded">+{{ $schedule->poa->participants->count() - 3 }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($schedule->isDraft())
                                                <form action="{{ route('jadwal.update', $schedule) }}" method="POST" class="flex items-center gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="date" name="start_date" value="{{ $schedule->start_date->format('Y-m-d') }}" class="text-sm border-gray-300 rounded px-2 py-1">
                                                    <span>-</span>
                                                    <input type="date" name="end_date" value="{{ $schedule->end_date->format('Y-m-d') }}" class="text-sm border-gray-300 rounded px-2 py-1">
                                                    <button type="submit" class="text-teal-600 hover:text-teal-800" title="Simpan">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-sm text-gray-700">{{ $schedule->formatted_date_range }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $schedule->duration_days }} hari
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($schedule->status === 'draft')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-edit mr-1"></i>Draft
                                                </span>
                                            @elseif($schedule->status === 'finalized')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-lock mr-1"></i>Terkunci
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-check mr-1"></i>Diklaim
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($schedule->isDraft())
                                                <button class="text-gray-400 hover:text-gray-600" title="Catatan">
                                                    <i class="fas fa-sticky-note"></i>
                                                </button>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- POAs Without Schedule -->
            @if($poasWithoutSchedule->isNotEmpty())
                <div class="mt-8 bg-gray-50 rounded-2xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        POA Belum Terjadwal ({{ $poasWithoutSchedule->count() }})
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">POA berikut belum memiliki jadwal untuk bulan ini. Klik "Generate Jadwal" untuk membuat jadwal otomatis.</p>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                        @foreach($poasWithoutSchedule->take(10) as $poa)
                            <li>{{ $poa->kegiatan }}</li>
                        @endforeach
                        @if($poasWithoutSchedule->count() > 10)
                            <li class="text-gray-400">... dan {{ $poasWithoutSchedule->count() - 10 }} lainnya</li>
                        @endif
                    </ul>
                </div>
            @endif

        </div>
    </div>

    <!-- Generate Modal -->
    <div id="generateModal" class="fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
            <form action="{{ route('jadwal.generate') }}" method="POST">
                @csrf
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-teal-100">
                        <i class="fas fa-magic text-teal-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Generate Jadwal Otomatis</h3>
                    <div class="mt-4 text-left">
                        <p class="text-sm text-gray-500 mb-4">Sistem akan menganalisa semua POA dan menyusun jadwal agar tidak ada bentrok pegawai.</p>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Bulan</label>
                                <select name="month" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tahun</label>
                                <select name="year" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                    @for($y = 2024; $y <= 2027; $y++)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-xs text-yellow-700">
                                <strong>Catatan:</strong> Jadwal draft yang sudah ada akan dihapus dan digenerate ulang. Jadwal yang sudah dikunci tidak akan terpengaruh.
                            </p>
                        </div>
                    </div>
                    <div class="items-center px-4 py-3 mt-4">
                        <div class="flex space-x-3">
                            <button type="submit" class="px-4 py-2 bg-teal-600 text-white text-base font-medium rounded-lg w-full shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-300">
                                Generate Sekarang
                            </button>
                            <button type="button" onclick="document.getElementById('generateModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-lg w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
