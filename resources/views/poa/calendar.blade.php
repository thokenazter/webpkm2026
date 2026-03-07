<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kalender Kegiatan BOK') }}
            </h2>
            <form method="GET" action="{{ route('poa.calendar') }}" class="flex items-center gap-2">
                <label for="year" class="text-sm font-medium text-gray-700">Tahun:</label>
                <select name="year" id="year" onchange="this.form.submit()" 
                    class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6" x-data="calendarFilter()" x-init="init()">
        <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-4 sm:px-6 py-3 sm:py-4">
                    <h3 class="text-base sm:text-lg font-semibold text-white">Jadwal Pelaksanaan Kegiatan Program BOK Tahun {{ $selectedYear }}</h3>
                    <p class="text-green-100 text-xs sm:text-sm mt-1">
                        Total: <span x-text="filteredCount"></span> kegiatan
                        <span x-show="searchQuery || selectedKomponen" class="ml-1">(difilter dari {{ count($calendarData) }})</span>
                    </p>
                </div>

                <!-- Search & Filter Bar -->
                <div class="p-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Search Input -->
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                x-model="searchQuery" 
                                @input="filterData()"
                                placeholder="Cari nama kegiatan..." 
                                class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                            <button x-show="searchQuery" 
                                @click="searchQuery = ''; filterData()" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Komponen Filter -->
                        <div class="sm:w-64">
                            <select x-model="selectedKomponen" 
                                @change="filterData()"
                                class="block w-full py-2 px-3 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Komponen</option>
                                @foreach($availableKomponen as $komponen)
                                    <option value="{{ $komponen }}">{{ $komponen }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Clear Filters -->
                        <button x-show="searchQuery || selectedKomponen" 
                            @click="clearFilters()"
                            class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </button>
                    </div>
                    
                    <!-- Quick Filter Chips -->
                    <div class="mt-3 hidden md:flex flex-wrap gap-2">
                        @foreach($availableKomponen as $komponen)
                            <button @click="toggleKomponen('{{ $komponen }}')"
                                :class="selectedKomponen === '{{ $komponen }}' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                                class="px-3 py-1 text-xs font-medium border rounded-full transition-colors">
                                {{ $komponen }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block">
                    <div class="calendar-container overflow-x-auto max-h-[60vh]">
                        <table class="min-w-full divide-y divide-gray-200 border-collapse">
                            <thead class="sticky top-0 z-10">
                                <tr class="bg-gradient-to-r from-blue-500 to-blue-600 shadow-md">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider min-w-[280px] bg-blue-600 sticky left-0 z-20">
                                        Kegiatan
                                    </th>
                                    @php
                                        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                                    @endphp
                                    @foreach($monthNames as $idx => $name)
                                        <th class="px-2 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider w-20
                                            {{ $idx < 4 ? 'bg-green-600' : ($idx < 8 ? 'bg-yellow-600' : 'bg-red-500') }}">
                                            {{ $name }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="(row, index) in filteredData" :key="row.id">
                                    <tr class="hover:bg-blue-50 transition-colors" :class="index % 2 == 0 ? 'bg-white' : 'bg-gray-50'">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 bg-gray-100 sticky left-0 z-10 border-r border-gray-200">
                                            <a :href="'{{ route('poa.index') }}/' + row.id" class="hover:text-blue-600 hover:underline block">
                                                <span class="font-semibold" x-text="row.kegiatan"></span>
                                            </a>
                                            <span class="text-xs text-gray-500 block mt-1" x-text="row.komponen"></span>
                                        </td>
                                        <template x-for="m in 12" :key="m">
                                            <td class="px-2 py-3 text-center border-r border-gray-100">
                                                <template x-if="row.months[m] && row.months[m].has_activity">
                                                    <button @click="openModal(row.id, m)"
                                                        class="inline-flex items-center justify-center w-full px-2 py-1 text-xs font-semibold rounded-md border-2 cursor-pointer hover:opacity-80 hover:shadow-md transition-all"
                                                        :class="[getMonthClass(row.months[m]), row.months[m].is_mine ? 'mine-tooltip' : '']"
                                                        :title="row.months[m].is_mine ? 'Anda ditugaskan bulan ini' : ('Klik untuk detail — ' + (row.months[m].claimed ? 'Sudah diklaim' : 'Belum diklaim'))">
                                                        <span x-text="row.months[m].count > 0 ? row.months[m].count + 'x' : '✓'"></span>
                                                        <span x-show="row.months[m].is_mine" class="ml-0.5 text-[10px]">👤</span>
                                                    </button>
                                                </template>
                                                <template x-if="!row.months[m] || !row.months[m].has_activity">
                                                    <span class="text-gray-300">—</span>
                                                </template>
                                            </td>
                                        </template>
                                    </tr>
                                </template>
                                <tr x-show="filteredData.length === 0">
                                    <td colspan="13" class="px-4 py-8 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        <p class="mt-2">Tidak ada kegiatan yang sesuai dengan filter.</p>
                                        <button @click="clearFilters()" class="mt-2 text-blue-600 hover:underline text-sm">Reset Filter</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden">
                    <div class="p-4 space-y-4 max-h-[60vh] overflow-y-auto">
                        <template x-for="row in filteredData" :key="row.id">
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                    <a :href="'{{ route('poa.index') }}/' + row.id" class="text-sm font-semibold text-gray-900 hover:text-blue-600" x-text="row.kegiatan"></a>
                                    <p class="text-xs text-gray-500 mt-1" x-text="row.komponen"></p>
                                </div>
                                <div class="p-3">
                                    <div class="grid grid-cols-6 gap-1">
                                        <template x-for="m in 12" :key="m">
                                            <div class="text-center">
                                                <span class="text-[10px] text-gray-500 block mb-1" x-text="['J','F','M','A','M','J','J','A','S','O','N','D'][m-1]"></span>
                                                <template x-if="row.months[m] && row.months[m].has_activity">
                                                    <button @click="openModal(row.id, m)"
                                                        class="block w-8 h-8 mx-auto rounded-full flex items-center justify-center text-xs font-bold cursor-pointer hover:opacity-80 transition-all"
                                                        :class="getMobileMonthClass(row.months[m])"
                                                        :title="row.months[m].is_mine ? 'Anda ditugaskan' : ''">
                                                        <span x-text="row.months[m].is_mine ? '👤' : '✓'"></span>
                                                    </button>
                                                </template>
                                                <template x-if="!row.months[m] || !row.months[m].has_activity">
                                                    <span class="block w-8 h-8 mx-auto rounded-full bg-gray-100 text-gray-300 flex items-center justify-center text-xs">—</span>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div x-show="filteredData.length === 0" class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <p class="mt-2">Tidak ada kegiatan yang sesuai dengan filter.</p>
                            <button @click="clearFilters()" class="mt-2 text-blue-600 hover:underline text-sm">Reset Filter</button>
                        </div>
                    </div>
                </div>

                <!-- Legend -->
                <div class="p-4 sm:p-6 bg-gray-50 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Keterangan:</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-6">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-6 h-6 rounded-full bg-green-500"></span>
                            <span class="text-xs sm:text-sm text-gray-600"><strong>Tahap I</strong> (Jan–Apr)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-6 h-6 rounded-full bg-yellow-500"></span>
                            <span class="text-xs sm:text-sm text-gray-600"><strong>Tahap II</strong> (Mei–Agu)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-6 h-6 rounded-full bg-red-500"></span>
                            <span class="text-xs sm:text-sm text-gray-600"><strong>Tahap III</strong> (Sep–Des)</span>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                            <span class="inline-block w-4 h-4 rounded bg-green-100 border-2 border-green-400"></span>
                            Belum diklaim
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="inline-block w-4 h-4 rounded bg-green-500"></span>
                            Sudah diklaim
                        </span>
                        <span class="flex items-center gap-1 ml-4">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Klik cell bulan untuk detail
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <div x-show="modalOpen" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto" 
            style="display: none;">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/50" @click="closeModal()"></div>
            
            <!-- Modal Panel -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="modalOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    @keydown.escape.window="closeModal()"
                    class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg mx-auto overflow-hidden">
                    
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b"
                        :class="{
                            'bg-gradient-to-r from-green-500 to-green-600': modalData && modalData.tahap === 1,
                            'bg-gradient-to-r from-yellow-500 to-yellow-600': modalData && modalData.tahap === 2,
                            'bg-gradient-to-r from-red-500 to-red-600': modalData && modalData.tahap === 3,
                            'bg-gray-500': !modalData
                        }">
                        <div class="flex items-start justify-between">
                            <div class="pr-8">
                                <h3 class="text-lg font-bold text-white" x-text="modalData ? modalData.kegiatan : ''"></h3>
                                <p class="text-sm mt-1 opacity-90 text-white" x-text="modalData ? modalData.bulan + ' ' + modalData.tahun : ''"></p>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-white/20 text-white"
                                    x-text="modalData ? 'Tahap ' + ['', 'I', 'II', 'III'][modalData.tahap] : ''"></span>
                            </div>
                            <button @click="closeModal()" class="text-white hover:text-gray-200 mt-1">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div x-show="claimStep === 'detail'" class="px-6 py-5 max-h-[60vh] overflow-y-auto">
                        <!-- Loading -->
                        <div x-show="modalLoading" class="flex items-center justify-center py-8">
                            <svg class="animate-spin h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="ml-3 text-gray-500">Memuat data...</span>
                        </div>

                        <!-- Error -->
                        <div x-show="modalError" class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <p class="mt-2 text-red-600" x-text="modalError"></p>
                        </div>

                        <!-- Content -->
                        <div x-show="modalData && !modalLoading && !modalError" class="space-y-5">
                            
                            <!-- Status -->
                            <div class="flex items-center gap-3">
                                <template x-if="modalData && modalData.claimed">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        Sudah Diklaim
                                    </span>
                                </template>
                                <template x-if="modalData && !modalData.claimed && modalData.marked">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.414L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                        Ditandai Aktif
                                    </span>
                                </template>
                                <template x-if="modalData && !modalData.claimed && !modalData.marked">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                        Belum Diklaim
                                    </span>
                                </template>
                            </div>

                            <!-- Jadwal SPPT/SPPD -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Jadwal Kegiatan
                                </h4>
                                <template x-if="modalData && modalData.schedules && modalData.schedules.length > 0">
                                    <div class="space-y-2">
                                        <template x-for="s in modalData.schedules" :key="s.type">
                                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="text-xs font-bold px-2 py-0.5 rounded"
                                                        :class="s.type === 'SPPT' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'"
                                                        x-text="s.type"></span>
                                                    <template x-if="s.nomor_surat">
                                                        <span class="text-sm font-medium text-gray-700" x-text="'No. ' + s.nomor_surat"></span>
                                                    </template>
                                                </div>
                                                <template x-if="s.tanggal_surat">
                                                    <div class="flex items-start gap-2 text-xs text-gray-600 mb-1">
                                                        <span class="font-medium text-gray-500 w-24 flex-shrink-0">Tgl Surat:</span>
                                                        <span x-text="s.tanggal_surat"></span>
                                                    </div>
                                                </template>
                                                <template x-if="s.tanggal_kegiatan">
                                                    <div class="flex items-start gap-2 text-xs text-gray-600">
                                                        <span class="font-medium text-gray-500 w-24 flex-shrink-0">Tgl Kegiatan:</span>
                                                        <span x-text="s.tanggal_kegiatan"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="modalData && (!modalData.schedules || modalData.schedules.length === 0)">
                                    <p class="text-sm text-gray-400 italic bg-gray-50 rounded-lg p-3 border border-dashed border-gray-300">
                                        ℹ️ Belum ada jadwal SPPT/SPPD untuk bulan ini.
                                    </p>
                                </template>
                            </div>

                            <!-- Pelaksana -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Pelaksana
                                </h4>
                                <template x-if="modalData && !modalData.no_participants && modalData.participants.length > 0">
                                    <div class="space-y-1">
                                        <template x-for="(p, i) in modalData.participants" :key="i">
                                            <div class="flex items-center gap-3 bg-gray-50 rounded-lg px-3 py-2 border border-gray-200">
                                                <span class="flex-shrink-0 w-7 h-7 rounded-full bg-blue-500 text-white flex items-center justify-center text-xs font-bold" x-text="i + 1"></span>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-800" x-text="p.nama"></p>
                                                    <template x-if="p.role">
                                                        <p class="text-xs text-gray-500" x-text="p.role"></p>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="modalData && modalData.no_participants">
                                    <p class="text-sm text-amber-600 bg-amber-50 rounded-lg p-3 border border-dashed border-amber-300">
                                        ⚠️ Data pegawai belum diinput oleh admin.
                                    </p>
                                </template>
                            </div>

                            <!-- Alokasi Dana -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
                                    Alokasi Dana
                                </h4>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm text-gray-600">Total Anggaran POA</span>
                                        <span class="text-sm font-semibold text-gray-800" x-text="modalData ? formatRupiah(modalData.planned_total) : '-'"></span>
                                    </div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm text-gray-600">Frekuensi Bulan Ini</span>
                                        <span class="text-sm font-semibold text-gray-800" x-text="modalData ? (modalData.monthly_count + 'x kegiatan') : '-'"></span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-semibold text-gray-700">Alokasi Bulan Ini</span>
                                        <span class="text-base font-bold" 
                                            :class="modalData && modalData.estimated_budget > 0 ? 'text-green-600' : 'text-gray-400'"
                                            x-text="modalData ? formatRupiah(modalData.estimated_budget) : '-'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Claim Form Step -->
                    <div x-show="claimStep === 'form' && !modalLoading" class="px-6 py-5 max-h-[60vh] overflow-y-auto">
                        <!-- Loading claim prep -->
                        <div x-show="claimLoading" class="flex items-center justify-center py-8">
                            <svg class="animate-spin h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="ml-3 text-gray-500">Memuat data desa...</span>
                        </div>

                        <div x-show="!claimLoading && claimPrep" class="space-y-4">
                            <p class="text-sm text-gray-600 bg-blue-50 rounded-lg p-3 border border-blue-200">
                                💡 Sistem akan otomatis membuat <strong>LPJ SPPT/SPPD</strong>, <strong>Dokumen Word</strong>, dan <strong>Tiba Berangkat</strong> berdasarkan data yang telah diinput.
                            </p>

                            <!-- Reclaim warning -->
                            <template x-if="claimPrep && claimPrep.is_reclaim && claimPrep.can_claim">
                                <div class="bg-red-50 border border-red-300 rounded-lg p-4 text-sm text-red-800">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                        <div>
                                            <strong>Klaim Ulang!</strong> Bulan ini sudah pernah diklaim. Jika dilanjutkan, data berikut akan <strong>dihapus permanen</strong>:
                                            <ul class="mt-1 ml-4 list-disc text-xs text-red-700">
                                                <li>LPJ SPPT & SPPD yang sudah dibuat</li>
                                                <li>Dokumen Word yang sudah digenerate</li>
                                                <li>Lembar Tiba Berangkat beserta detailnya</li>
                                            </ul>
                                            <p class="mt-1 text-xs">Kemudian akan dibuat ulang dengan data terbaru dari pengaturan admin.</p>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Cannot claim message -->
                            <template x-if="claimPrep && !claimPrep.can_claim">
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-700">
                                    ⚠️ <span x-text="claimPrep.reason"></span>
                                </div>
                            </template>

                            <!-- Claim preview -->
                            <template x-if="claimPrep && claimPrep.can_claim">
                                <div class="space-y-3">
                                    <!-- SPPT (Darat) -->
                                    <template x-if="claimPrep.target_darat > 0">
                                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                            <h5 class="text-sm font-semibold text-blue-800 mb-2 flex items-center gap-2">
                                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-bold">SPPT</span>
                                                Desa Darat
                                                <span class="text-xs text-blue-500 font-normal">(<span x-text="claimPrep.target_darat"></span> desa)</span>
                                            </h5>
                                            <template x-if="claimPrep.auto_desa_darat">
                                                <p class="text-sm text-blue-900 font-medium flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                    <span x-text="claimPrep.auto_desa_darat"></span>
                                                </p>
                                            </template>
                                            <template x-if="!claimPrep.auto_desa_darat">
                                                <p class="text-xs text-amber-700 bg-amber-50 rounded p-2 border border-amber-200">
                                                    ⚠️ Jumlah desa darat (<span x-text="claimPrep.target_darat"></span>) tidak sesuai — desa akan dikosongkan, silakan isi manual di halaman POA setelah dibuat.
                                                </p>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- SPPD (Seberang) -->
                                    <template x-if="claimPrep.target_seberang > 0">
                                        <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                                            <h5 class="text-sm font-semibold text-purple-800 mb-2 flex items-center gap-2">
                                                <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded text-xs font-bold">SPPD</span>
                                                Desa Seberang
                                                <span class="text-xs text-purple-500 font-normal">(<span x-text="claimPrep.target_seberang"></span> desa)</span>
                                            </h5>
                                            <template x-if="claimPrep.auto_desa_seberang">
                                                <p class="text-sm text-purple-900 font-medium flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                    <span x-text="claimPrep.auto_desa_seberang"></span>
                                                </p>
                                            </template>
                                            <template x-if="!claimPrep.auto_desa_seberang">
                                                <p class="text-xs text-amber-700 bg-amber-50 rounded p-2 border border-amber-200">
                                                    ⚠️ Jumlah desa seberang (<span x-text="claimPrep.target_seberang"></span>) tidak sesuai — desa akan dikosongkan, silakan isi manual di halaman POA setelah dibuat.
                                                </p>
                                            </template>
                                        </div>
                                    </template>

                                    <template x-if="claimPrep.target_darat === 0 && claimPrep.target_seberang === 0">
                                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-amber-700">
                                            ⚠️ RAB tidak mengatur target desa untuk kegiatan ini.
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Claim Success Step -->
                    <div x-show="claimStep === 'success'" class="px-6 py-8 text-center">
                        <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-3 text-lg font-bold text-gray-900">LPJ Berhasil Dibuat!</h3>

                        <!-- Download Buttons -->
                        <div class="mt-5 space-y-2">
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Download Dokumen</p>
                            <div class="flex flex-wrap justify-center gap-2">
                                <template x-if="claimCreatedIds.sppt">
                                    <button @click="window.open('/lpj/' + claimCreatedIds.sppt + '/download', '_blank')"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:shadow-md transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Download SPPT
                                    </button>
                                </template>
                                <template x-if="claimCreatedIds.sppd">
                                    <button @click="window.open('/lpj/' + claimCreatedIds.sppd + '/download', '_blank')"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-purple-700 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 hover:shadow-md transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Download SPPD
                                    </button>
                                </template>
                                <template x-if="claimCreatedIds.tb">
                                    <button @click="window.open('/tiba-berangkats/' + claimCreatedIds.tb + '/download', '_blank')"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 hover:shadow-md transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Download Tiba Berangkat
                                    </button>
                                </template>
                                <template x-for="item in (claimCreatedIds.item_opsional || [])" :key="item.id">
                                    <button @click="window.open('/item-opsional/' + item.id + '/download', '_blank')"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 hover:shadow-md transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <span x-text="'Download Kwitansi ' + item.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button @click="closeModal(); location.reload()" class="px-5 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div x-show="modalData && !modalLoading && claimStep !== 'success'" class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <a :href="modalData ? '{{ route('poa.index') }}/' + modalData.poa_id : '#'" 
                                class="text-sm text-blue-600 hover:underline font-medium"
                                x-show="claimStep === 'detail' && modalData && modalData.poa_id">
                                Lihat Detail POA →
                            </a>
                            <button x-show="claimStep === 'form'" @click="claimStep = 'detail'"
                                class="text-sm text-gray-600 hover:underline font-medium">
                                ← Kembali
                            </button>
                        </div>
                        <div class="flex items-center gap-2">
                            <!-- Buat LPJ button (always visible) -->
                            <button x-show="claimStep === 'detail' && modalData"
                                @click="startClaim()"
                                :class="modalData && modalData.claimed ? 'bg-amber-600 hover:bg-amber-700' : 'bg-green-600 hover:bg-green-700'"
                                class="px-4 py-2 text-sm font-medium text-white rounded-lg shadow-sm transition-colors flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span x-text="modalData && modalData.claimed ? 'Klaim Ulang LPJ' : 'Buat LPJ'"></span>
                            </button>
                            <!-- Submit claim button -->
                            <button x-show="claimStep === 'form' && claimPrep && claimPrep.can_claim && (claimPrep.target_darat > 0 || claimPrep.target_seberang > 0)"
                                @click="submitClaim()"
                                :disabled="claimSubmitting"
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 shadow-sm transition-colors flex items-center gap-1.5 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg x-show="claimSubmitting" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="claimSubmitting ? 'Memproses...' : (claimPrep && claimPrep.is_reclaim ? 'Hapus Lama & Buat Ulang' : 'Buat LPJ & Tiba Berangkat')"></span>
                            </button>
                            <button @click="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .calendar-container { position: relative; }
        .calendar-container thead { position: sticky; top: 0; z-index: 10; }
        .calendar-container thead th { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .calendar-container tbody td:first-child { position: sticky; left: 0; background: #f9fafb; box-shadow: 2px 0 4px rgba(0,0,0,0.05); }

        /* Personalized highlight animations */
        @keyframes pulse-glow-green {
            0%, 100% { box-shadow: 0 0 4px rgba(34, 197, 94, 0.4); }
            50% { box-shadow: 0 0 12px rgba(34, 197, 94, 0.7), 0 0 20px rgba(34, 197, 94, 0.3); }
        }
        @keyframes pulse-glow-yellow {
            0%, 100% { box-shadow: 0 0 4px rgba(234, 179, 8, 0.4); }
            50% { box-shadow: 0 0 12px rgba(234, 179, 8, 0.7), 0 0 20px rgba(234, 179, 8, 0.3); }
        }
        @keyframes pulse-glow-red {
            0%, 100% { box-shadow: 0 0 4px rgba(239, 68, 68, 0.4); }
            50% { box-shadow: 0 0 12px rgba(239, 68, 68, 0.7), 0 0 20px rgba(239, 68, 68, 0.3); }
        }
        .mine-glow-green { animation: pulse-glow-green 2s ease-in-out infinite; }
        .mine-glow-yellow { animation: pulse-glow-yellow 2s ease-in-out infinite; }
        .mine-glow-red { animation: pulse-glow-red 2s ease-in-out infinite; }

        /* Tooltip for personalized cells */
        .mine-tooltip { position: relative; }
        .mine-tooltip::after {
            content: 'Anda ditugaskan bulan ini';
            position: absolute;
            bottom: calc(100% + 6px);
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: white;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 6px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
            z-index: 30;
        }
        .mine-tooltip::before {
            content: '';
            position: absolute;
            bottom: calc(100% + 2px);
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid transparent;
            border-top-color: #1e293b;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
            z-index: 30;
        }
        .mine-tooltip:hover::after, .mine-tooltip:hover::before {
            opacity: 1;
        }

        @media print {
            .no-print { display: none !important; }
            table { font-size: 9px; }
            th, td { padding: 3px !important; }
            .calendar-container { max-height: none !important; overflow: visible !important; }
            thead { position: relative !important; }
            tbody td:first-child { position: relative !important; }
            .mine-glow-green, .mine-glow-yellow, .mine-glow-red { animation: none !important; }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function calendarFilter() {
            return {
                allData: @json($calendarData),
                filteredData: [],
                searchQuery: '',
                selectedKomponen: '',
                filteredCount: 0,

                // Modal state
                modalOpen: false,
                modalLoading: false,
                modalError: null,
                modalData: null,
                currentPoaId: null,
                currentMonth: null,

                // Claim state
                claimStep: 'detail', // 'detail' | 'form' | 'success'
                claimLoading: false,
                claimPrep: null,
                claimSubmitting: false,
                claimSuccessMsg: '',
                claimCreatedIds: { sppt: null, sppd: null, tb: null, item_opsional: [] },

                init() {
                    this.filterData();
                },

                filterData() {
                    let result = this.allData;
                    if (this.searchQuery.trim() !== '') {
                        const query = this.searchQuery.toLowerCase().trim();
                        result = result.filter(row => 
                            row.kegiatan.toLowerCase().includes(query) ||
                            row.komponen.toLowerCase().includes(query) ||
                            row.rincian_menu.toLowerCase().includes(query)
                        );
                    }
                    if (this.selectedKomponen !== '') {
                        result = result.filter(row => row.komponen === this.selectedKomponen);
                    }
                    // Sort: kegiatan milik user (is_mine) muncul di atas
                    result.sort((a, b) => {
                        const aMine = Object.values(a.months).some(m => m && m.is_mine);
                        const bMine = Object.values(b.months).some(m => m && m.is_mine);
                        if (aMine && !bMine) return -1;
                        if (!aMine && bMine) return 1;
                        return a.kegiatan.localeCompare(b.kegiatan);
                    });
                    this.filteredData = result;
                    this.filteredCount = result.length;
                },

                toggleKomponen(komponen) {
                    this.selectedKomponen = this.selectedKomponen === komponen ? '' : komponen;
                    this.filterData();
                },

                clearFilters() {
                    this.searchQuery = '';
                    this.selectedKomponen = '';
                    this.filterData();
                },

                // Modal methods
                async openModal(poaId, month) {
                    this.modalOpen = true;
                    this.modalLoading = true;
                    this.modalError = null;
                    this.modalData = null;
                    this.currentPoaId = poaId;
                    this.currentMonth = month;
                    this.claimStep = 'detail';
                    this.claimPrep = null;

                    try {
                        const response = await fetch(`/poa/${poaId}/calendar-detail/${month}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        });
                        if (!response.ok) throw new Error('Gagal memuat data');
                        const data = await response.json();
                        data.poa_id = poaId;
                        this.modalData = data;
                    } catch (error) {
                        this.modalError = error.message || 'Terjadi kesalahan saat memuat data.';
                    } finally {
                        this.modalLoading = false;
                    }
                },

                closeModal() {
                    this.modalOpen = false;
                    this.modalData = null;
                    this.modalError = null;
                    this.claimStep = 'detail';
                    this.claimPrep = null;
                    this.claimSubmitting = false;
                },

                // Claim methods
                async startClaim() {
                    this.claimStep = 'form';
                    this.claimLoading = true;
                    this.claimPrep = null;

                    try {
                        const response = await fetch(`/poa/${this.currentPoaId}/calendar-claim-prep/${this.currentMonth}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        });
                        if (!response.ok) throw new Error('Gagal memuat data desa');
                        this.claimPrep = await response.json();
                    } catch (error) {
                        this.claimPrep = { can_claim: false, reason: error.message };
                    } finally {
                        this.claimLoading = false;
                    }
                },

                async submitClaim() {
                    this.claimSubmitting = true;

                    const desaDarat = this.claimPrep.auto_desa_darat || '';
                    const desaSeberang = this.claimPrep.auto_desa_seberang || '';
                    const jumlahDarat = this.claimPrep.auto_desa_darat ? this.claimPrep.target_darat : 0;
                    const jumlahSeberang = this.claimPrep.auto_desa_seberang ? this.claimPrep.target_seberang : 0;

                    try {
                        const formData = new FormData();
                        formData.append('month', this.currentMonth);
                        formData.append('jumlah_desa_darat', jumlahDarat);
                        formData.append('desa_tujuan_darat', desaDarat);
                        formData.append('jumlah_desa_seberang', jumlahSeberang);
                        formData.append('desa_tujuan_seberang', desaSeberang);

                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                        const response = await fetch(`/poa/${this.currentPoaId}/claim`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        if (response.ok) {
                            let data = null;
                            try { data = await response.json(); } catch(e) {}

                            if (data && data.success) {
                                this.claimCreatedIds = {
                                    sppt: data.sppt_lpj_id || null,
                                    sppd: data.sppd_lpj_id || null,
                                    tb: data.tiba_berangkat_id || null,
                                    item_opsional: data.item_opsional || [],
                                };
                                const msgs = [];
                                if (data.sppt_lpj_id) msgs.push('LPJ SPPT');
                                if (data.sppd_lpj_id) msgs.push('LPJ SPPD');
                                if (data.tiba_berangkat_id) msgs.push('Tiba Berangkat');
                                this.claimSuccessMsg = (msgs.length > 0 ? msgs.join(', ') : 'LPJ') + ' berhasil dibuat! Silakan download dokumen di bawah.';
                            } else {
                                const msgs = [];
                                if (jumlahDarat > 0) msgs.push('LPJ SPPT');
                                if (jumlahSeberang > 0) msgs.push('LPJ SPPD');
                                this.claimSuccessMsg = (msgs.length > 0 ? msgs.join(' dan ') : 'LPJ') + ' + Tiba Berangkat berhasil dibuat.';
                            }
                            this.claimStep = 'success';
                        } else {
                            const text = await response.text();
                            let errorMsg = 'Gagal membuat LPJ.';
                            try {
                                const json = JSON.parse(text);
                                errorMsg = json.message || json.error || errorMsg;
                            } catch(e) {
                                if (text.includes('error')) errorMsg = 'Gagal membuat LPJ. Cek halaman POA untuk detail.';
                            }
                            alert(errorMsg);
                        }
                    } catch (error) {
                        alert('Terjadi kesalahan jaringan: ' + error.message);
                    } finally {
                        this.claimSubmitting = false;
                    }
                },

                formatRupiah(amount) {
                    if (amount == null || amount === 0) return 'Rp 0';
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(amount));
                },

                getMonthClass(monthData) {
                    if (!monthData) return '';
                    const tahap = monthData.tahap;
                    const claimed = monthData.claimed;
                    const mine = monthData.is_mine;
                    let base = '';
                    if (tahap === 1) base = claimed ? 'bg-green-500 text-white border-green-600 shadow-sm' : 'bg-green-100 text-green-700 border-green-400';
                    else if (tahap === 2) base = claimed ? 'bg-yellow-500 text-white border-yellow-600 shadow-sm' : 'bg-yellow-100 text-yellow-700 border-yellow-400';
                    else if (tahap === 3) base = claimed ? 'bg-red-500 text-white border-red-600 shadow-sm' : 'bg-red-100 text-red-700 border-red-400';
                    if (mine && !claimed) {
                        const glow = tahap === 1 ? 'mine-glow-green' : tahap === 2 ? 'mine-glow-yellow' : 'mine-glow-red';
                        base += ' border-[3px] ' + glow;
                    }
                    return base;
                },

                getMobileMonthClass(monthData) {
                    if (!monthData) return '';
                    const tahap = monthData.tahap;
                    const claimed = monthData.claimed;
                    const mine = monthData.is_mine;
                    let base = '';
                    if (tahap === 1) base = claimed ? 'bg-green-500 text-white' : 'bg-green-100 text-green-700 border-2 border-green-400';
                    else if (tahap === 2) base = claimed ? 'bg-yellow-500 text-white' : 'bg-yellow-100 text-yellow-700 border-2 border-yellow-400';
                    else if (tahap === 3) base = claimed ? 'bg-red-500 text-white' : 'bg-red-100 text-red-700 border-2 border-red-400';
                    if (mine && !claimed) {
                        const glow = tahap === 1 ? 'mine-glow-green' : tahap === 2 ? 'mine-glow-yellow' : 'mine-glow-red';
                        base += ' ring-2 ring-offset-1 ' + glow;
                    } else if (mine && claimed) {
                        base += ' ring-2 ring-offset-1 ring-white';
                    }
                    return base;
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
