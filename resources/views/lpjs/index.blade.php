<x-app-layout>
    {{-- <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Data LPJ BOK Puskesmas') }}
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
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <h1 class="text-2xl font-bold mb-2">Data LPJ BOK Puskesmas</h1>
                            <p class="text-indigo-100">Kelola dan pantau semua Laporan Pertanggungjawaban</p>
                        </div>
                        <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                            <a href="{{ route('lpjs.create') }}" class="bg-white text-indigo-600 px-6 py-2 rounded-lg font-semibold hover:bg-indigo-50 transition duration-200 shadow-md btn-modern">
                                <i class="fas fa-plus mr-2"></i>Buat LPJ Baru
                            </a>
                            @if(auth()->check() && auth()->user()->isAdmin())
                            <button onclick="document.getElementById('autoScheduleModal').classList.remove('hidden')" class="bg-indigo-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-indigo-600 transition duration-200 shadow-md btn-modern">
                                <i class="fas fa-calendar-check mr-2"></i>Auto Schedule
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
                    @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-2xl mb-6 flex items-center justify-between shadow-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3 text-green-500"></i>
                            <span>{{ session('success') }}</span>
                    </div>
                            @php
                                $pairIds = session('download_pair_ids');
                            @endphp
                            @if ($pairIds && is_array($pairIds) && count($pairIds) >= 2)
                                @php
                                    // Fetch both LPJs to provide individual download buttons
                                    $pairLpjs = \App\Models\Lpj::whereIn('id', $pairIds)->get();
                                @endphp
                                @foreach ($pairLpjs as $p)
                                    <a href="{{ route('lpj.download', $p) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg flex items-center ml-4 transition duration-200">
                                        <i class="fas fa-download mr-2"></i>Download {{ $p->type }}
                                    </a>
                                @endforeach
                                @php
                                    $tbId = session('tiba_berangkat_id');
                                    $tb = $tbId ? \App\Models\TibaBerangkat::find($tbId) : null;
                                @endphp
                                @if ($tb)
                                    <a href="{{ route('tiba-berangkats.download', $tb) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg flex items-center ml-4 transition duration-200">
                                        <i class="fas fa-route mr-2"></i>Download Tiba Berangkat
                                    </a>
                                @endif
                            @elseif (session('show_download'))
                                @php
                                    $showDownloadId = session('show_download');
                                    $lpjForDownload = $showDownloadId ? \App\Models\Lpj::where('id', $showDownloadId)->where('created_by', auth()->id())->first() : null;
                                @endphp
                                @if ($lpjForDownload)
                                    <a href="{{ route('lpj.download', $lpjForDownload) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg flex items-center ml-4 transition duration-200">
                                        <i class="fas fa-download mr-2"></i>Download Dokumen
                                    </a>
                                @endif
                            @endif
                        </div>
                    @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-2xl mb-6 flex items-center shadow-lg">
                    <i class="fas fa-exclamation-triangle mr-3 text-red-500"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @php
                $reviewTbId = session('tiba_berangkat_review_id');
                $reviewTb = null;
                if ($reviewTbId) {
                    $reviewTb = \App\Models\TibaBerangkat::with(['details.pejabatTtd'])
                        ->where('id', $reviewTbId)
                        ->where('created_by', auth()->id())
                        ->first();
                }
                $reviewPairIds = session('download_pair_ids');
            @endphp
            @if ($reviewTb)
                <!-- Auto Tiba Berangkat Review Modal -->
                <div id="autoTbModal" class="fixed z-30 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white rounded-2xl px-6 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-route text-emerald-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tiba Berangkat dibuat otomatis</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">Kami menyusun urutan desa berdasarkan tanggal kunjungan dari SPPT dan SPPD. Anda dapat mengubah tanggal langsung di sini sebelum download.</p>
                                        <form id="autoTbForm" data-action="{{ route('tiba-berangkats.quick_update', $reviewTb) }}" class="mt-3 max-h-64 overflow-y-auto border rounded-lg p-3 bg-gray-50">
                                            @csrf
                                            <div class="grid grid-cols-1 gap-3">
                                                <div class="flex items-center gap-3">
                                                    <label class="text-sm font-medium text-gray-700 w-32">No. Surat</label>
                                                    <input type="text" name="no_surat" value="{{ $reviewTb->no_surat }}" class="flex-1 border rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500" />
                                                </div>
                                                @foreach ($reviewTb->details->sortBy('tanggal_kunjungan') as $d)
                                                    <div class="flex items-center gap-3">
                                                        <span class="text-sm text-gray-700 w-32 font-medium">{{ $d->pejabatTtd->desa }}</span>
                                                        <input type="date" class="tb-date-input border rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500" data-detail-id="{{ $d->id }}" value="{{ $d->tanggal_kunjungan->format('Y-m-d') }}" />
                                                    </div>
                                                @endforeach
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-2">
                                <a href="{{ route('tiba-berangkats.edit', $reviewTb) }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Tinjau & Edit</a>
                                @if (is_array($reviewPairIds) && count($reviewPairIds) >= 2)
                                    <button id="saveAndDownloadTripleBtn" data-tbid="{{ $reviewTb->id }}" data-lpjids="{{ implode(',', $reviewPairIds) }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:w-auto sm:text-sm">Simpan & Download 3 Dokumen</button>
                                @endif
                                <button id="dismissAutoTb" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total LPJ -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 dashboard-card">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-calculator text-white text-lg"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total LPJ</dt>
                                    <dd class="text-2xl font-bold text-gray-900 stat-number">{{ number_format($stats['total_lpjs']) }}</dd>
                                    <dd class="text-xs text-blue-600 font-medium">
                                        @if($stats['by_type']->get('SPPT', 0) > 0)
                                            {{ $stats['by_type']['SPPT'] }} SPPT
                                        @endif
                                        @if($stats['by_type']->get('SPPD', 0) > 0)
                                            {{ $stats['by_type']['SPPD'] }} SPPD
                                        @endif
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Peserta -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 dashboard-card">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-users text-white text-lg"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Peserta</dt>
                                    <dd class="text-2xl font-bold text-gray-900 stat-number">{{ number_format($stats['total_participants']) }}</dd>
                                    <dd class="text-xs text-green-600 font-medium">Semua kegiatan</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Transport -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 dashboard-card">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-car text-white text-lg"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Transport</dt>
                                    <dd class="text-2xl font-bold text-gray-900 stat-number">
                                        Rp {{ number_format($stats['total_transport'], 0, ',', '.') }}
                                    </dd>
                                    <dd class="text-xs text-orange-600 font-medium">Biaya perjalanan</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Anggaran -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 dashboard-card">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-money-bill-wave text-white text-lg"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Anggaran</dt>
                                    <dd class="text-2xl font-bold text-gray-900 stat-number">
                                        Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}
                                    </dd>
                                    <dd class="text-xs text-purple-600 font-medium">
                                        Rata-rata: Rp {{ number_format($stats['avg_per_lpj'], 0, ',', '.') }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Controls -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
                <div class="flex flex-col lg:flex-row gap-6">
                    
                    <!-- Search and Filters -->
                    <div class="flex-1">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            
                            <!-- Search -->
                            <div class="relative">
                                <input type="text" 
                                       id="searchInput"
                                       placeholder="Cari kegiatan, no surat..." 
                                       value="{{ request('search') }}"
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <div id="searchLoading" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                                    <div class="loading-spinner"></div>
                                </div>
                            </div>
                            
                            <!-- Type Filter -->
                            <div>
                                <select id="typeFilter" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="all">Semua Tipe</option>
                                    <option value="SPPT" {{ request('type') == 'SPPT' ? 'selected' : '' }}>SPPT</option>
                                    <option value="SPPD" {{ request('type') == 'SPPD' ? 'selected' : '' }}>SPPD</option>
                                </select>
                            </div>

                            <!-- Month/Year Filter -->
                            <div class="flex gap-2">
                                <select id="monthFilter" class="flex-1 py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Bulan</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                                <select id="yearFilter" class="flex-1 py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Tahun</option>
                                    @for($year = 2023; $year <= 2026; $year++)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                    </div>

                            <!-- Per Page -->
                            <div>
                                <select id="perPageFilter" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 per halaman</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per halaman</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per halaman</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per halaman</option>
                                </select>
                            </div>
                        </div>

                        <!-- Active Filters Display -->
                        <div id="activeFilters" class="mt-4 flex flex-wrap gap-2 {{ request()->hasAny(['search', 'type', 'month', 'year']) ? '' : 'hidden' }}">
                            <span class="text-sm text-gray-600 font-medium">Filter aktif:</span>
                            @if(request('search'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Search: "{{ request('search') }}"
                                    <button type="button" class="ml-1 text-blue-600 hover:text-blue-800" onclick="clearFilter('search')">×</button>
                                </span>
                            @endif
                            @if(request('type') && request('type') !== 'all')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Tipe: {{ request('type') }}
                                    <button type="button" class="ml-1 text-green-600 hover:text-green-800" onclick="clearFilter('type')">×</button>
                                </span>
                            @endif
                            @if(request('month') && request('year'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ DateTime::createFromFormat('!m', request('month'))->format('F') }} {{ request('year') }}
                                    <button type="button" class="ml-1 text-purple-600 hover:text-purple-800" onclick="clearFilter('month')">×</button>
                                </span>
                            @endif
                            <button type="button" id="clearAllFilters" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 hover:bg-gray-200">
                                Hapus semua filter
                            </button>
                        </div>
                    </div>

                    <!-- Bulk Actions -->
                    <div class="lg:w-80">
                        <div class="border-l border-gray-200 pl-6">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Aksi Massal</h3>
                            
                            <div class="space-y-3">
                                <!-- Show All Toggle -->
                                <div class="flex items-center justify-between">
                                    <label class="text-sm text-gray-600">Tampilkan semua</label>
                                    <button id="showAllToggle" 
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $showAll ? 'bg-indigo-600' : 'bg-gray-200' }}"
                                            data-show-all="{{ $showAll ? 'true' : 'false' }}">
                                        <span class="sr-only">Toggle show all</span>
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $showAll ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                </div>

                                <!-- Bulk Selection -->
                                <div id="bulkActions" class="space-y-2 {{ $showAll || (!$showAll && $stats['total_lpjs'] > 0) ? '' : 'hidden' }}">
                                    <div class="flex items-center justify-between">
                                        <label class="text-sm text-gray-600">
                                            <input type="checkbox" id="selectAll" class="mr-2 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            Pilih semua
                                        </label>
                                        <span id="selectedCount" class="text-xs text-gray-500">0 dipilih</span>
                                    </div>
                                    
                                    <button id="bulkDeleteBtn" 
                                            class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 disabled:bg-gray-300 disabled:cursor-not-allowed"
                                            disabled>
                                        <i class="fas fa-trash mr-2"></i>Hapus Terpilih
                                    </button>
                                </div>
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                    <div id="tableContainer">
                    @include('lpjs.partials.table', ['lpjs' => $lpjs, 'showAll' => $showAll])
                </div>
            </div>
        </div>
    </div>

    @if (session('suggest_sppd_for'))
    <!-- Suggest Create SPPD Modal -->
    <div id="suggestSppdModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-12 sm:w-12">
                            <i class="fas fa-forward text-blue-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Lanjut buat SPPD?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">SPPT berhasil dibuat. Apakah Anda ingin melanjutkan membuat SPPD untuk kegiatan dan peserta yang sama? Anda hanya perlu mengubah tanggal kegiatan dan tanggal surat keluar.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse">
                    <a href="{{ route('lpjs.create_from', ['lpj' => session('suggest_sppd_for'), 'to' => 'SPPD']) }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Ya, buat SPPD</a>
                    <button type="button" id="dismissSuggestSppd" data-tb-from="{{ session('suggest_tb_from') ?? session('suggest_sppd_for') }}" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Nanti saja</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @php $suggestTbFrom = session('suggest_tb_from'); @endphp
    <!-- Suggest Create Tiba Berangkat Modal -->
    <div id="suggestTbModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="tb-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-12 sm:w-12">
                            <i class="fas fa-route text-emerald-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="tb-modal-title">Buat Tiba Berangkat sekarang?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Anda dapat membuat lembar Tiba Berangkat sekarang. Kami akan memprefill daftar desa dari dokumen yang barusan dibuat. Tanggal bisa Anda atur sebelum download.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse">
                    <a id="goCreateTbBtn" href="#" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">Ya, buat Tiba Berangkat</a>
                    <button type="button" id="dismissSuggestTb" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Nanti saja</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Delete Confirmation Modal -->
    <div id="bulkDeleteModal" class="fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Konfirmasi Hapus Massal</h3>
                <div class="mt-4">
                    <p class="text-sm text-gray-600">
                        Anda akan menghapus <strong id="bulkDeleteCount">0</strong> LPJ yang dipilih.
                    </p>
                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-700">
                            <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Semua data LPJ termasuk peserta dan dokumen terkait akan dihapus permanen.
                        </p>
                    </div>
                </div>
                <div class="items-center px-4 py-3">
                    <div class="flex space-x-3">
                        <button id="confirmBulkDeleteBtn" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-lg w-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Ya, Hapus Semua
                        </button>
                        <button id="cancelBulkDeleteBtn" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-lg w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Modal -->
    <div id="actionModal" class="fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
            <div class="mt-3">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="mx-auto flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100">
                            <i class="fas fa-tasks text-indigo-600"></i>
                        </div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 ml-3">Aksi LPJ</h3>
                    </div>
                    <button onclick="closeActionModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- LPJ Info -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <p class="text-sm text-gray-600 mb-2"><strong>No. Surat:</strong> <span id="action-modal-no-surat"></span></p>
                    <p class="text-sm text-gray-600 mb-2"><strong>Kegiatan:</strong> <span id="action-modal-kegiatan" class="break-words"></span></p>
                    <p class="text-sm text-gray-600 mb-2"><strong>Jumlah Peserta:</strong> <span id="action-modal-peserta"></span> orang</p>
                    <p class="text-sm text-gray-600"><strong>Total Anggaran:</strong> <span id="action-modal-total"></span></p>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="actionView()" class="flex flex-col items-center justify-center p-4 border-2 border-indigo-200 rounded-lg hover:border-indigo-400 hover:bg-indigo-50 transition-all duration-200 group">
                        <i class="fas fa-eye text-2xl text-indigo-500 mb-2 group-hover:text-indigo-600"></i>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-600">Lihat Detail</span>
                    </button>
                    
                    <button onclick="actionEdit()" class="flex flex-col items-center justify-center p-4 border-2 border-yellow-200 rounded-lg hover:border-yellow-400 hover:bg-yellow-50 transition-all duration-200 group">
                        <i class="fas fa-edit text-2xl text-yellow-500 mb-2 group-hover:text-yellow-600"></i>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-600">Edit LPJ</span>
                    </button>
                    
                    <button onclick="actionDownload()" class="flex flex-col items-center justify-center p-4 border-2 border-green-200 rounded-lg hover:border-green-400 hover:bg-green-50 transition-all duration-200 group">
                        <i class="fas fa-download text-2xl text-green-500 mb-2 group-hover:text-green-600"></i>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-green-600">Download</span>
                    </button>
                    
                    <button onclick="actionDelete()" class="flex flex-col items-center justify-center p-4 border-2 border-red-200 rounded-lg hover:border-red-400 hover:bg-red-50 transition-all duration-200 group">
                        <i class="fas fa-trash text-2xl text-red-500 mb-2 group-hover:text-red-600"></i>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-red-600">Hapus LPJ</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Single Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Konfirmasi Hapus LPJ</h3>
                <div class="mt-4 text-left">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2"><strong>No. Surat:</strong> <span id="modal-no-surat"></span></p>
                        <p class="text-sm text-gray-600 mb-2"><strong>Kegiatan:</strong> <span id="modal-kegiatan" class="break-words"></span></p>
                        <p class="text-sm text-gray-600 mb-2"><strong>Jumlah Peserta:</strong> <span id="modal-peserta"></span> orang</p>
                        <p class="text-sm text-gray-600"><strong>Total Anggaran:</strong> <span id="modal-total"></span></p>
                    </div>
                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-700">
                            <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                </div>
                <div class="items-center px-4 py-3">
                    <div class="flex space-x-3">
                        <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-lg w-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Ya, Hapus LPJ
                        </button>
                        <button id="cancelDeleteBtn" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-lg w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto Schedule Modal -->
    <div id="autoScheduleModal" class="fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
            <form action="{{ route('lpjs.auto_schedule') }}" method="POST">
                @csrf
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100">
                        <i class="fas fa-magic text-indigo-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Auto Schedule & Numbering</h3>
                    <div class="mt-4 text-left">
                        <p class="text-sm text-gray-500 mb-4">Fitur ini akan menyusun ulang tanggal kegiatan agar tidak bentrok antar pegawai, dan mengurutkan nomor surat secara otomatis.</p>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Bulan</label>
                                <select name="month" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tahun</label>
                                <select name="year" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @for($y = 2024; $y <= 2026; $y++)
                                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-xs text-yellow-700">
                                <strong>Perhatian:</strong> Tanggal kegiatan yang sudah ada akan ditimpa jika ada bentrok. Nomor surat akan diurutkan ulang dari 001.
                            </p>
                        </div>
                    </div>
                    <div class="items-center px-4 py-3 mt-4">
                        <div class="flex space-x-3">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-lg w-full shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                Proses Sekarang
                            </button>
                            <button type="button" onclick="document.getElementById('autoScheduleModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-lg w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Delete Form -->
    <form id="bulkDeleteForm" action="{{ route('lpjs.bulk-delete') }}" method="POST" style="display: none;">
        @csrf
        <div id="bulkDeleteInputs"></div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize variables
            const searchInput = document.getElementById('searchInput');
            const searchLoading = document.getElementById('searchLoading');
            const activeFilters = document.getElementById('activeFilters');
            const tableContainer = document.getElementById('tableContainer');
            const typeFilter = document.getElementById('typeFilter');
            const monthFilter = document.getElementById('monthFilter');
            const yearFilter = document.getElementById('yearFilter');
            const perPageFilter = document.getElementById('perPageFilter');
            const showAllToggle = document.getElementById('showAllToggle');
            const selectAll = document.getElementById('selectAll');
            const selectedCount = document.getElementById('selectedCount');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
            
            let searchTimeout;
            let selectedLpjs = new Set();

            // Multi download helper (without ZIP)
            function triggerDownload(url) {
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = url;
                document.body.appendChild(iframe);
                // Clean up later
                setTimeout(() => { try { document.body.removeChild(iframe); } catch (e) {} }, 60000);
            }

            const downloadPairBtn = document.getElementById('downloadPairBtn');
            if (downloadPairBtn) {
                const ids = (downloadPairBtn.dataset.ids || '').split(',').map(s => s.trim()).filter(Boolean);
                window.downloadPairIds = ids; // expose for other download triggers (action menu)
                downloadPairBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (!ids.length) return;

                    // Trigger downloads with slight delay between them
                    ids.forEach((id, idx) => {
                        setTimeout(() => {
                            const url = `/lpj/${id}/download`;
                            triggerDownload(url);
                        }, idx * 400);
                    });
                });
            }

            // Auto Tiba Berangkat Review Modal logic
            const autoTbModal = document.getElementById('autoTbModal');
            const dismissAutoTb = document.getElementById('dismissAutoTb');
            if (autoTbModal && dismissAutoTb) {
                dismissAutoTb.addEventListener('click', function() {
                    autoTbModal.style.display = 'none';
                });
            }

            const saveAndDownloadTripleBtn = document.getElementById('saveAndDownloadTripleBtn');
            if (saveAndDownloadTripleBtn) {
                saveAndDownloadTripleBtn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const tbid = this.dataset.tbid;
                    const ids = (this.dataset.lpjids || '').split(',').map(s => s.trim()).filter(Boolean);
                    const formEl = document.getElementById('autoTbForm');
                    if (!formEl) return;
                    const action = formEl.dataset.action;
                    // Collect payload
                    const details = Array.from(document.querySelectorAll('.tb-date-input')).map(input => ({
                        id: parseInt(input.dataset.detailId, 10),
                        tanggal_kunjungan: input.value
                    }));
                    const noSuratInput = formEl.querySelector('input[name="no_surat"]');
                    const payload = {
                        no_surat: noSuratInput ? noSuratInput.value : '',
                        details
                    };

                    try {
                        const res = await fetch(action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(payload)
                        });
                        if (!res.ok) throw new Error('Gagal menyimpan perubahan');
                        // trigger downloads: LPJs then TB
                        const urls = [];
                        ids.forEach(id => urls.push(`/lpj/${id}/download`));
                        if (tbid) urls.push(`/tiba-berangkats/${tbid}/download`);
                        urls.forEach((url, idx) => setTimeout(() => triggerDownload(url), idx * 500));
                        if (autoTbModal) autoTbModal.style.display = 'none';
                    } catch (err) {
                        alert(err.message || 'Terjadi kesalahan saat menyimpan data.');
                    }
                });
            }

            // Show suggest SPPD modal if available
            @if (session('suggest_sppd_for'))
                const suggestModal = document.getElementById('suggestSppdModal');
                if (suggestModal) {
                    suggestModal.classList.remove('hidden');
                    const dismissBtn = document.getElementById('dismissSuggestSppd');
                    if (dismissBtn) {
                        dismissBtn.addEventListener('click', function() {
                            suggestModal.classList.add('hidden');
                            // After dismissing SPPD suggestion, show TB suggestion using the same LPJ as source
                            const tbFrom = this.dataset.tbFrom;
                            const tbModal = document.getElementById('suggestTbModal');
                            const goBtn = document.getElementById('goCreateTbBtn');
                            if (tbModal && goBtn && tbFrom) {
                                goBtn.href = `{{ url('/tiba-berangkats/auto-from-lpj') }}/${tbFrom}`;
                                tbModal.classList.remove('hidden');
                            }
                        });
                    }
                }
            @endif

            // If only TB suggestion is set (e.g., user created SPPD first), show TB suggestion directly
            @if ($suggestTbFrom && !session('suggest_sppd_for'))
                (function(){
                    const tbModal = document.getElementById('suggestTbModal');
                    const goBtn = document.getElementById('goCreateTbBtn');
                    if (tbModal && goBtn) {
                        goBtn.href = `{{ url('/tiba-berangkats/auto-from-lpj') }}/{{ $suggestTbFrom }}`;
                        tbModal.classList.remove('hidden');
                    }
                })();
            @endif

            // Dismiss TB modal
            (function(){
                const tbDismiss = document.getElementById('dismissSuggestTb');
                const tbModal = document.getElementById('suggestTbModal');
                if (tbDismiss && tbModal) {
                    tbDismiss.addEventListener('click', function(){ tbModal.classList.add('hidden'); });
                }
            })();
            
            // Show All Toggle
            showAllToggle.addEventListener('click', function() {
                const isShowAll = this.dataset.showAll === 'true';
                const newShowAll = !isShowAll;
                
                // Update toggle appearance
                this.dataset.showAll = newShowAll.toString();
                if (newShowAll) {
                    this.classList.add('bg-indigo-600');
                    this.classList.remove('bg-gray-200');
                    this.querySelector('span:last-child').classList.add('translate-x-6');
                    this.querySelector('span:last-child').classList.remove('translate-x-1');
                } else {
                    this.classList.remove('bg-indigo-600');
                    this.classList.add('bg-gray-200');
                    this.querySelector('span:last-child').classList.remove('translate-x-6');
                    this.querySelector('span:last-child').classList.add('translate-x-1');
                }
                
                // Apply filter
                applyFilters();
            });

            // Filter functions
            function applyFilters() {
                showLoading();
                
                const params = new URLSearchParams();
                
                // Get current filter values
                if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
                if (typeFilter.value && typeFilter.value !== 'all') params.set('type', typeFilter.value);
                if (monthFilter.value) params.set('month', monthFilter.value);
                if (yearFilter.value) params.set('year', yearFilter.value);
                if (perPageFilter.value && perPageFilter.value !== '10') params.set('per_page', perPageFilter.value);
                if (showAllToggle.dataset.showAll === 'true') params.set('show_all', 'true');
                
                // Make request
                fetch(`{{ route('lpjs.index') }}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    }
                })
                .then(response => response.text())
                .then(html => {
                    tableContainer.innerHTML = html;
                    updateActiveFilters();
                    resetBulkSelection();
                    hideLoading();
                })
                .catch(error => {
                    console.error('Filter error:', error);
                    hideLoading();
                });
            }

            function clearFilter(filterName) {
                switch(filterName) {
                    case 'search':
                        searchInput.value = '';
                        break;
                    case 'type':
                        typeFilter.value = 'all';
                        break;
                    case 'month':
                        monthFilter.value = '';
                        yearFilter.value = '';
                        break;
                }
                applyFilters();
            }

            function showLoading() {
                searchLoading.classList.remove('hidden');
            }

            function hideLoading() {
                    searchLoading.classList.add('hidden');
            }

            function updateActiveFilters() {
                const hasFilters = searchInput.value.trim() || 
                                 (typeFilter.value && typeFilter.value !== 'all') || 
                                 monthFilter.value || yearFilter.value;
                
                if (hasFilters) {
                    activeFilters.classList.remove('hidden');
                } else {
                    activeFilters.classList.add('hidden');
                }
            }

            function resetBulkSelection() {
                selectedLpjs.clear();
                selectAll.checked = false;
                updateBulkActionState();
                
                // Uncheck all individual checkboxes
                document.querySelectorAll('.lpj-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
            }

            function updateBulkActionState() {
                selectedCount.textContent = `${selectedLpjs.size} dipilih`;
                bulkDeleteBtn.disabled = selectedLpjs.size === 0;
            }

            // Event listeners
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    applyFilters();
                }, 300);
            });
            
            [typeFilter, monthFilter, yearFilter, perPageFilter].forEach(filter => {
                filter.addEventListener('change', applyFilters);
            });

            document.getElementById('clearAllFilters').addEventListener('click', function() {
                searchInput.value = '';
                typeFilter.value = 'all';
                monthFilter.value = '';
                yearFilter.value = '';
                applyFilters();
            });

            // Bulk selection
            selectAll.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.lpj-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                    if (this.checked) {
                        selectedLpjs.add(checkbox.value);
                    } else {
                        selectedLpjs.delete(checkbox.value);
                    }
                });
                updateBulkActionState();
            });

            // Handle individual checkbox changes (delegated)
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('lpj-checkbox')) {
                    if (e.target.checked) {
                        selectedLpjs.add(e.target.value);
                    } else {
                        selectedLpjs.delete(e.target.value);
                        selectAll.checked = false;
                    }
                    updateBulkActionState();
                    
                    // Update select all checkbox
                    const allCheckboxes = document.querySelectorAll('.lpj-checkbox');
                    const checkedCheckboxes = document.querySelectorAll('.lpj-checkbox:checked');
                    selectAll.checked = allCheckboxes.length > 0 && allCheckboxes.length === checkedCheckboxes.length;
                }
            });

            // Bulk delete
            bulkDeleteBtn.addEventListener('click', function() {
                if (selectedLpjs.size > 0) {
                    document.getElementById('bulkDeleteCount').textContent = selectedLpjs.size;
                    document.getElementById('bulkDeleteModal').classList.remove('hidden');
                }
            });

            document.getElementById('confirmBulkDeleteBtn').addEventListener('click', function() {
                const form = document.getElementById('bulkDeleteForm');
                const inputsContainer = document.getElementById('bulkDeleteInputs');
                
                // Clear previous inputs
                inputsContainer.innerHTML = '';
                
                // Add selected LPJ IDs
                selectedLpjs.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'lpj_ids[]';
                    input.value = id;
                    inputsContainer.appendChild(input);
                });
                
                form.submit();
            });

            document.getElementById('cancelBulkDeleteBtn').addEventListener('click', function() {
                document.getElementById('bulkDeleteModal').classList.add('hidden');
            });

            // Handle pagination links (delegated)
            document.addEventListener('click', function(e) {
                if (e.target.closest('.pagination a')) {
                    e.preventDefault();
                    const url = e.target.closest('.pagination a').href;
                    
                    showLoading();
                    
                    fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html',
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                        resetBulkSelection();
                        hideLoading();
                    })
                    .catch(error => {
                        console.error('Pagination error:', error);
                        hideLoading();
                    });
                }
            });
            
            // Single delete functionality
            window.currentDeleteId = null;

            window.confirmDelete = function(id, noSurat, kegiatan, peserta, total) {
                window.currentDeleteId = id;
                
                document.getElementById('modal-no-surat').textContent = noSurat;
                document.getElementById('modal-kegiatan').textContent = kegiatan;
                document.getElementById('modal-peserta').textContent = peserta;
                document.getElementById('modal-total').textContent = total;
                
                document.getElementById('deleteModal').classList.remove('hidden');
            };

            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (window.currentDeleteId) {
                    document.getElementById('delete-form-' + window.currentDeleteId).submit();
                }
            });

            document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
                document.getElementById('deleteModal').classList.add('hidden');
                window.currentDeleteId = null;
            });

            // Close modals when clicking outside or pressing Escape
            [document.getElementById('deleteModal'), document.getElementById('bulkDeleteModal'), document.getElementById('actionModal')].forEach(modal => {
                modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                    window.currentDeleteId = null;
                }
                });
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    [document.getElementById('deleteModal'), document.getElementById('bulkDeleteModal'), document.getElementById('actionModal')].forEach(modal => {
                        if (!modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                        window.currentDeleteId = null;
                    }
                    });
                }
            });
        });

        // Action Modal Functions
        window.closeActionModal = function() {
            document.getElementById('actionModal').classList.add('hidden');
        };

        window.actionView = function() {
            if (window.currentActionLpjId) {
                window.location.href = `/lpjs/${window.currentActionLpjId}`;
            }
        };

        window.actionEdit = function() {
            if (window.currentActionLpjId) {
                window.location.href = `/lpjs/${window.currentActionLpjId}/edit`;
            }
        };

        window.actionDownload = function() {
            if (window.currentActionLpjId) {
                try {
                    const currentId = String(window.currentActionLpjId);
                    const pairIds = (window.downloadPairIds || []).map(String);
                    if (pairIds.length >= 2 && pairIds.includes(currentId)) {
                        // Trigger both downloads (no ZIP)
                        pairIds.forEach((id, idx) => {
                            setTimeout(() => {
                                const url = `/lpj/${id}/download`;
                                const iframe = document.createElement('iframe');
                                iframe.style.display = 'none';
                                iframe.src = url;
                                document.body.appendChild(iframe);
                                setTimeout(() => { try { document.body.removeChild(iframe); } catch (e) {} }, 60000);
                            }, idx * 400);
                        });
                    } else {
                        // Single download
                        const downloadUrl = `/lpj/${currentId}/download`;
                        window.location.href = downloadUrl;
                    }
                } catch (error) {
                    console.error('Download error:', error);
                    alert('Terjadi kesalahan saat mengunduh dokumen. Silakan coba lagi.');
                }
            } else {
                console.error('No LPJ ID available for download');
                alert('ID LPJ tidak ditemukan. Silakan refresh halaman dan coba lagi.');
            }
        };

        window.actionDelete = function() {
            if (window.currentActionLpjId && window.currentActionLpjData) {
                // Close action modal
                window.closeActionModal();
                
                // Show delete confirmation modal
                document.getElementById('modal-no-surat').textContent = window.currentActionLpjData.noSurat;
                document.getElementById('modal-kegiatan').textContent = window.currentActionLpjData.kegiatan;
                document.getElementById('modal-peserta').textContent = window.currentActionLpjData.peserta;
                document.getElementById('modal-total').textContent = window.currentActionLpjData.total;
                
                // Set current delete ID
                window.currentDeleteId = window.currentActionLpjId;
                
                // Show delete modal
                document.getElementById('deleteModal').classList.remove('hidden');
            }
        };
    </script>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>
