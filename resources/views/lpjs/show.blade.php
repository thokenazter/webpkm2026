<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-file-alt text-2xl mr-3"></i>
                                <h1 class="text-2xl font-bold">Detail LPJ BOK</h1>
                            </div>
                            <div class="mb-3">
                                <div class="flex items-center text-lg">
                                    <span class="font-semibold mr-2">{{ $lpj->no_surat }}</span>
                                    <span class="mx-2">•</span>
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $lpj->type == 'SPPT' ? 'bg-green-500 text-white' : 'bg-purple-500 text-white' }}">
                                        {{ $lpj->type }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-blue-100 text-lg">{{ $lpj->kegiatan }}</p>
                            <div class="mt-3 flex flex-wrap items-center text-sm text-blue-200">
                                <span class="flex items-center mr-4">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $lpj->tanggal_kegiatan }}
                                </span>
                                <span class="flex items-center mr-4">
                                    <i class="fas fa-users mr-1"></i>
                                    {{ $lpj->participants->count() }} peserta
                                </span>
                                @if($lpj->jumlah_desa_darat > 0 || $lpj->jumlah_desa_seberang > 0)
                                    <span class="flex items-center">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        @if($lpj->jumlah_desa_darat > 0)
                                            {{ $lpj->jumlah_desa_darat }} desa darat
                                        @endif
                                        @if($lpj->jumlah_desa_seberang > 0)
                                            {{ $lpj->jumlah_desa_seberang }} desa seberang
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 lg:mt-0 flex flex-wrap gap-3">
                            <a href="{{ route('lpj.download', $lpj) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold transition duration-200 shadow-md">
                                <i class="fas fa-download mr-2"></i>Download Word
                            </a>
                            <form action="{{ route('lpj.regenerate', $lpj) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg font-semibold transition duration-200 shadow-md">
                                    <i class="fas fa-sync-alt mr-2"></i>Regenerate
                                </button>
                            </form>
                            <a href="{{ route('lpjs.edit', $lpj) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg font-semibold transition duration-200 shadow-md">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </a>
                            <a href="{{ route('lpjs.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200 shadow-md">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-500 text-white rounded-xl">
                            <i class="fas fa-car text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Transport</p>
                            <p class="text-xl font-bold text-blue-600">
                                Rp {{ number_format($lpj->participants->sum('transport_amount'), 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 bg-green-500 text-white rounded-xl">
                            <i class="fas fa-money-bill-wave text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Uang Harian</p>
                            <p class="text-xl font-bold text-green-600">
                                Rp {{ number_format($lpj->participants->sum('per_diem_amount'), 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 bg-purple-500 text-white rounded-xl">
                            <i class="fas fa-coins text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Anggaran</p>
                            <p class="text-xl font-bold text-purple-600">
                                Rp {{ number_format($lpj->participants->sum('total_amount'), 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 bg-indigo-500 text-white rounded-xl">
                            <i class="fas fa-users text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Jumlah Peserta</p>
                            <p class="text-xl font-bold text-indigo-600">{{ $lpj->participants->count() }} orang</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="space-y-8">
                <!-- LPJ Information Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 bg-blue-500 text-white rounded-xl">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-semibold text-blue-700">Informasi LPJ</h2>
                                <p class="text-sm text-gray-600">Detail lengkap laporan pertanggungjawaban</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                                        <i class="fas fa-tag text-indigo-500 mr-2"></i>Tipe LPJ
                                    </h3>
                                    <div class="flex items-center">
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $lpj->type == 'SPPT' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $lpj->type }}
                                        </span>
                                        <span class="ml-2 text-sm text-gray-600">
                                            {{ $lpj->type == 'SPPT' ? 'Surat Perintah Perjalanan Tugas' : 'Surat Perintah Perjalanan Dinas' }}
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                                        <i class="fas fa-hashtag text-purple-500 mr-2"></i>No. Surat
                                    </h3>
                                    <p class="text-gray-900 font-medium">{{ $lpj->no_surat }}</p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                                        <i class="fas fa-calendar-alt text-green-500 mr-2"></i>Tanggal Surat
                                    </h3>
                                    <p class="text-gray-900">{{ $lpj->tanggal_surat }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                                        <i class="fas fa-clipboard-list text-blue-500 mr-2"></i>Kegiatan
                                    </h3>
                                    <p class="text-gray-900 font-medium">{{ $lpj->kegiatan }}</p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                                        <i class="fas fa-calendar-check text-orange-500 mr-2"></i>Tanggal Kegiatan
                                    </h3>
                                    <p class="text-gray-900">{{ $lpj->tanggal_kegiatan }}</p>
                                </div>

                                @if($lpj->transport_mode)
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                                        <i class="fas fa-ship text-teal-500 mr-2"></i>Mode Transport
                                    </h3>
                                    <p class="text-gray-900">{{ $lpj->transport_mode }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Desa Information -->
                        @if($lpj->jumlah_desa_darat > 0 || $lpj->jumlah_desa_seberang > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>Desa Tujuan
                            </h3>
                            <div class="space-y-3">
                                @if($lpj->jumlah_desa_darat > 0)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-map text-green-600 mr-2"></i>
                                        <span class="font-semibold text-green-800">{{ $lpj->jumlah_desa_darat }} Desa Darat</span>
                                    </div>
                                    <p class="text-green-700 ml-6">{{ $lpj->desa_tujuan_darat ?: 'Tidak ada data desa tujuan' }}</p>
                                </div>
                                @endif
                                @if($lpj->jumlah_desa_seberang > 0)
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-ship text-blue-600 mr-2"></i>
                                        <span class="font-semibold text-blue-800">{{ $lpj->jumlah_desa_seberang }} Desa Seberang</span>
                                    </div>
                                    <p class="text-blue-700 ml-6">{{ $lpj->desa_tujuan_seberang ?: 'Tidak ada data desa tujuan' }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($lpj->catatan)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-sticky-note text-yellow-500 mr-2"></i>Catatan
                            </h3>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <p class="text-yellow-800">{{ $lpj->catatan }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Participants Table -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 bg-indigo-500 text-white rounded-xl">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="ml-4">
                                    <h2 class="text-xl font-semibold text-blue-700">Daftar Peserta</h2>
                                    <p class="text-sm text-gray-600">{{ $lpj->participants->count() }} orang terlibat dalam kegiatan</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-indigo-600">{{ $lpj->participants->count() }}</div>
                                <div class="text-xs text-gray-500">Total Peserta</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($lpj->participants as $index => $participant)
                            <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200 hover:shadow-lg transition-shadow duration-200">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        @php $docEmployee = $participant->document_employee ?? $participant->employee; @endphp
                                        <div class="font-semibold text-lg text-gray-800">{{ $docEmployee?->nama ?? '-' }}</div>
                                        @if(!empty($docEmployee?->pangkat_golongan))
                                            {{-- <div class="text-xs text-gray-500 mt-0.5">{{ $docEmployee->pangkat_golongan }}</div> --}}
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">#{{ $index + 1 }}</div>
                                </div>
                                
                                <div class="space-y-2 text-sm">
                                    {{-- <div class="flex justify-between">
                                        <span class="text-gray-600">Role:</span>
                                        <span class="font-semibold text-gray-800">{{ $participant->role == 'KETUA' ? 'PJ' : $participant->role }}</span>
                                    </div> --}}

                                    {{-- <div class="flex justify-between">
                                        <span class="text-gray-600">Lama Tugas:</span>
                                        <span class="font-semibold text-gray-800">{{ $participant->lama_tugas_hari }} hari</span>
                                    </div> --}}

                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Transport:</span>
                                        <span class="font-semibold text-blue-600">Rp {{ number_format($participant->transport_amount, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Uang Harian:</span>
                                        <div class="text-right">
                                            @if($participant->per_diem_amount > 0)
                                                <span class="font-semibold text-green-600">Rp {{ number_format($participant->per_diem_amount, 0, ',', '.') }}</span>
                                                <div class="text-[10px] text-gray-500">{{ $participant->per_diem_days }} hari × Rp {{ number_format($participant->per_diem_rate, 0, ',', '.') }}</div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-semibold text-gray-700">Total:</span>
                                        <span class="text-lg font-bold text-purple-600">Rp {{ number_format($participant->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>