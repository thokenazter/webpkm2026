<x-app-layout>
    <div class="py-4 sm:py-6" x-data="dashboardCalendar()" x-init="init()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ═══════════════════════════════════════════════════════ --}}
            {{-- WELCOME CARD — Personal greeting for all users        --}}
            {{-- ═══════════════════════════════════════════════════════ --}}
            <div class="mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-5 sm:p-6 text-white relative overflow-hidden">
                    {{-- decorative circles --}}
                    <div class="absolute -top-6 -right-6 w-32 h-32 bg-white/5 rounded-full"></div>
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-white/5 rounded-full"></div>

                    <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            @if(!$isAdmin && $employeeName)
                                <h3 class="text-xl font-bold mb-1">Halo, {{ $employeeName }}! 👋</h3>
                                @if($myActivitiesThisMonth > 0)
                                    <p class="text-blue-100">
                                        Anda memiliki <span class="font-bold text-white">{{ $myActivitiesThisMonth }} kegiatan</span> di bulan {{ $currentMonthName }}
                                    </p>
                                @else
                                    <p class="text-blue-100">Belum ada kegiatan yang ditugaskan bulan ini</p>
                                @endif
                            @else
                                <h3 class="text-xl font-bold mb-1">Dashboard LPJ BOK Puskesmas</h3>
                                <p class="text-blue-100">Kelola Laporan Pertanggungjawaban dengan mudah dan efisien</p>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-3">
                            @if(!$isAdmin && $myActivitiesThisMonth > 0)
                                {{-- Personal quick stats pills --}}
                                <div class="flex gap-2">
                                    <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                                        <span class="w-2 h-2 bg-yellow-300 rounded-full animate-pulse"></span>
                                        {{ $myUnclaimedThisMonth }} belum klaim
                                    </span>
                                    <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                                        <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                                        {{ $myClaimedThisMonth }} selesai
                                    </span>
                                </div>
                            @endif
                            <a href="{{ route('poa.calendar') }}" class="bg-white text-blue-600 px-5 py-2 rounded-lg font-semibold hover:bg-blue-50 transition duration-200 shadow-md text-sm">
                                <i class="fas fa-calendar-alt mr-2"></i>Kalender Lengkap
                            </a>
                            @if($isAdmin)
                                <a href="{{ route('lpjs.create') }}" class="bg-blue-500 text-white px-5 py-2 rounded-lg font-semibold hover:bg-blue-400 transition duration-200 shadow-md text-sm">
                                    <i class="fas fa-plus mr-2"></i>Buat LPJ
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════ --}}
            {{-- MINI CALENDAR — Embedded calendar for all users       --}}
            {{-- ═══════════════════════════════════════════════════════ --}}
            <div class="mb-6">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-calendar-check text-blue-500"></i>
                                Kalender Kegiatan {{ $currentYear }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                @if(!$isAdmin)
                                    <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Kegiatan anda ditandai khusus</span>
                                @else
                                    Semua kegiatan POA tahun {{ $currentYear }}
                                @endif
                            </p>
                        </div>
                        {{-- Search --}}
                        <div class="relative w-full sm:w-64">
                            <input type="text" x-model="searchQuery" @input="filterData()" placeholder="Cari kegiatan..."
                                class="w-full pl-9 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                            <button x-show="searchQuery" @click="searchQuery = ''; filterData()" class="absolute right-2 top-2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Quick Filter Chips — desktop only --}}
                    <div class="hidden md:flex flex-wrap gap-2 px-5 py-3 border-b border-gray-50 bg-gray-50/50">
                        @foreach($availableKomponen as $komponen)
                            <button @click="toggleKomponen('{{ $komponen }}')"
                                :class="selectedKomponen === '{{ $komponen }}' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                                class="px-3 py-1 text-xs font-medium border rounded-full transition-colors">
                                {{ $komponen }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Desktop Table --}}
                    <div class="hidden md:block">
                        <div class="calendar-container overflow-x-auto max-h-[55vh]">
                            <table class="min-w-full divide-y divide-gray-200 border-collapse">
                                <thead class="sticky top-0 z-10">
                                    <tr class="bg-gradient-to-r from-blue-500 to-blue-600 shadow-md">
                                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-white uppercase tracking-wider min-w-[280px] bg-blue-600 sticky left-0 z-20">
                                            Kegiatan
                                        </th>
                                        @php $mNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des']; @endphp
                                        @foreach($mNames as $idx => $name)
                                            <th class="px-2 py-2.5 text-center text-xs font-semibold text-white uppercase tracking-wider w-16
                                                {{ $idx < 4 ? 'bg-green-600' : ($idx < 8 ? 'bg-yellow-600' : 'bg-red-500') }}">
                                                {{ $name }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="(row, index) in filteredData" :key="row.id">
                                        <tr class="hover:bg-blue-50 transition-colors" :class="index % 2 == 0 ? 'bg-white' : 'bg-gray-50'">
                                            <td class="px-4 py-2 text-sm font-medium text-gray-900 bg-gray-100 sticky left-0 z-10 border-r border-gray-200">
                                                @if($isAdmin)
                                                    <a :href="'{{ route('poa.index') }}/' + row.id" class="hover:text-blue-600 hover:underline block">
                                                        <span class="font-semibold text-xs" x-text="row.kegiatan"></span>
                                                    </a>
                                                @else
                                                    <span class="font-semibold text-xs block" x-text="row.kegiatan"></span>
                                                @endif
                                                <span class="text-[10px] text-gray-500 block mt-0.5" x-text="row.komponen"></span>
                                            </td>
                                            <template x-for="m in 12" :key="m">
                                                <td class="px-1 py-2 text-center border-r border-gray-100">
                                                    <template x-if="row.months[m] && row.months[m].has_activity">
                                                        <button @click="openModal(row.id, m)"
                                                            class="inline-flex items-center justify-center w-full px-1 py-0.5 text-[10px] font-semibold rounded border-2 cursor-pointer hover:opacity-80 hover:shadow-md transition-all"
                                                            :class="[getMonthClass(row.months[m]), row.months[m].is_mine ? 'mine-tooltip' : '']"
                                                            :title="row.months[m].is_mine ? 'Anda ditugaskan bulan ini' : (row.months[m].claimed ? 'Sudah diklaim' : 'Belum diklaim')">
                                                            <span x-text="row.months[m].count > 0 ? row.months[m].count + 'x' : '✓'"></span>
                                                            <span x-show="row.months[m].is_mine" class="ml-0.5 text-[9px]">👤</span>
                                                        </button>
                                                    </template>
                                                    <template x-if="!row.months[m] || !row.months[m].has_activity">
                                                        <span class="text-gray-300 text-[10px]">—</span>
                                                    </template>
                                                </td>
                                            </template>
                                        </tr>
                                    </template>
                                    <tr x-show="filteredData.length === 0">
                                        <td colspan="13" class="px-4 py-6 text-center text-gray-500 text-sm">
                                            Tidak ada kegiatan yang sesuai filter.
                                            <button @click="clearFilters()" class="text-blue-600 hover:underline ml-1">Reset</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Mobile Card View --}}
                    <div class="md:hidden">
                        <div class="p-3 space-y-3 max-h-[50vh] overflow-y-auto">
                            <template x-for="row in filteredData" :key="row.id">
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                    <div class="bg-gray-50 px-3 py-2 border-b border-gray-200">
                                        @if($isAdmin)
                                            <a :href="'{{ route('poa.index') }}/' + row.id" class="text-xs font-semibold text-gray-900 hover:text-blue-600" x-text="row.kegiatan"></a>
                                        @else
                                            <span class="text-xs font-semibold text-gray-900" x-text="row.kegiatan"></span>
                                        @endif
                                        <p class="text-[10px] text-gray-500 mt-0.5" x-text="row.komponen"></p>
                                    </div>
                                    <div class="p-2">
                                        <div class="grid grid-cols-6 gap-1">
                                            <template x-for="m in 12" :key="m">
                                                <div class="text-center">
                                                    <span class="text-[9px] text-gray-500 block mb-0.5" x-text="['J','F','M','A','M','J','J','A','S','O','N','D'][m-1]"></span>
                                                    <template x-if="row.months[m] && row.months[m].has_activity">
                                                        <button @click="openModal(row.id, m)"
                                                            class="block w-7 h-7 mx-auto rounded-full flex items-center justify-center text-[10px] font-bold cursor-pointer hover:opacity-80 transition-all"
                                                            :class="getMobileMonthClass(row.months[m])"
                                                            :title="row.months[m].is_mine ? 'Anda ditugaskan' : ''">
                                                            <span x-text="row.months[m].is_mine ? '👤' : '✓'"></span>
                                                        </button>
                                                    </template>
                                                    <template x-if="!row.months[m] || !row.months[m].has_activity">
                                                        <span class="block w-7 h-7 mx-auto rounded-full bg-gray-100 text-gray-300 flex items-center justify-center text-[10px]">—</span>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Calendar footer with animated hint --}}
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                        {{-- Animated info hint --}}
                        <div class="flex items-center justify-center gap-2 mb-2 calendar-hint">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-full text-xs font-medium border border-blue-200 shadow-sm">
                                <svg class="w-3.5 h-3.5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                                Klik pada bulan untuk lihat detail & generate LPJ
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-2">
                            <span class="text-xs text-gray-500">
                                Menampilkan <span class="font-semibold" x-text="filteredData.length"></span> kegiatan
                            </span>
                            <a href="{{ route('poa.calendar') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                Buka Kalender Lengkap <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ Detail Modal (same as calendar page) ═══ --}}
            <div x-show="modalOpen" 
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="fixed inset-0 bg-black/50" @click="closeModal()"></div>
                <div class="flex min-h-full items-center justify-center p-4">
                    <div x-show="modalOpen"
                        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        @keydown.escape.window="closeModal()"
                        class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg mx-auto overflow-hidden">

                        {{-- Modal Header --}}
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
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Modal Body: Detail --}}
                        <div x-show="claimStep === 'detail'" class="px-6 py-5 max-h-[60vh] overflow-y-auto">
                            <div x-show="modalLoading" class="flex items-center justify-center py-8">
                                <svg class="animate-spin h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span class="ml-3 text-gray-500">Memuat data...</span>
                            </div>
                            <div x-show="modalError" class="text-center py-8">
                                <p class="text-red-600" x-text="modalError"></p>
                            </div>
                            <div x-show="modalData && !modalLoading && !modalError" class="space-y-5">
                                {{-- Status --}}
                                <div class="flex items-center gap-3">
                                    <template x-if="modalData && modalData.claimed"><span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">✅ Sudah Diklaim</span></template>
                                    <template x-if="modalData && !modalData.claimed && modalData.marked"><span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">⏰ Ditandai Aktif</span></template>
                                    <template x-if="modalData && !modalData.claimed && !modalData.marked"><span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">⏳ Belum Diklaim</span></template>
                                </div>
                                {{-- Jadwal --}}
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-800 mb-2">📅 Jadwal Kegiatan</h4>
                                    <template x-if="modalData && modalData.schedules && modalData.schedules.length > 0">
                                        <div class="space-y-2">
                                            <template x-for="s in modalData.schedules" :key="s.type">
                                                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <span class="text-xs font-bold px-2 py-0.5 rounded" :class="s.type === 'SPPT' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'" x-text="s.type"></span>
                                                        <template x-if="s.nomor_surat"><span class="text-sm font-medium text-gray-700" x-text="'No. ' + s.nomor_surat"></span></template>
                                                    </div>
                                                    <template x-if="s.tanggal_surat"><div class="flex gap-2 text-xs text-gray-600 mb-1"><span class="font-medium text-gray-500 w-24">Tgl Surat:</span><span x-text="s.tanggal_surat"></span></div></template>
                                                    <template x-if="s.tanggal_kegiatan"><div class="flex gap-2 text-xs text-gray-600"><span class="font-medium text-gray-500 w-24">Tgl Kegiatan:</span><span x-text="s.tanggal_kegiatan"></span></div></template>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="modalData && (!modalData.schedules || modalData.schedules.length === 0)">
                                        <p class="text-sm text-gray-400 italic bg-gray-50 rounded-lg p-3 border border-dashed border-gray-300">ℹ️ Belum ada jadwal.</p>
                                    </template>
                                </div>
                                {{-- Pelaksana --}}
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-800 mb-2">👥 Pelaksana</h4>
                                    <template x-if="modalData && !modalData.no_participants && modalData.participants.length > 0">
                                        <div class="space-y-1">
                                            <template x-for="(p, i) in modalData.participants" :key="i">
                                                <div class="flex items-center gap-3 bg-gray-50 rounded-lg px-3 py-2 border border-gray-200">
                                                    <span class="flex-shrink-0 w-7 h-7 rounded-full bg-blue-500 text-white flex items-center justify-center text-xs font-bold" x-text="i + 1"></span>
                                                    <div><p class="text-sm font-medium text-gray-800" x-text="p.nama"></p><template x-if="p.role"><p class="text-xs text-gray-500" x-text="p.role"></p></template></div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="modalData && modalData.no_participants"><p class="text-sm text-amber-600 bg-amber-50 rounded-lg p-3 border border-dashed border-amber-300">⚠️ Data pegawai belum diinput oleh admin.</p></template>
                                </div>
                                {{-- Alokasi Dana --}}
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-800 mb-2">💰 Alokasi Dana</h4>
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="flex justify-between items-center mb-2"><span class="text-sm text-gray-600">Total Anggaran POA</span><span class="text-sm font-semibold text-gray-800" x-text="modalData ? formatRupiah(modalData.planned_total) : '-'"></span></div>
                                        <div class="flex justify-between items-center mb-2"><span class="text-sm text-gray-600">Frekuensi Bulan Ini</span><span class="text-sm font-semibold text-gray-800" x-text="modalData ? (modalData.monthly_count + 'x kegiatan') : '-'"></span></div>
                                        <hr class="my-2">
                                        <div class="flex justify-between items-center"><span class="text-sm font-semibold text-gray-700">Alokasi Bulan Ini</span><span class="text-base font-bold" :class="modalData && modalData.estimated_budget > 0 ? 'text-green-600' : 'text-gray-400'" x-text="modalData ? formatRupiah(modalData.estimated_budget) : '-'"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Claim Form Step --}}
                        <div x-show="claimStep === 'form' && !modalLoading" class="px-6 py-5 max-h-[60vh] overflow-y-auto">
                            <div x-show="claimLoading" class="flex items-center justify-center py-8">
                                <svg class="animate-spin h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span class="ml-3 text-gray-500">Memuat data desa...</span>
                            </div>
                            <div x-show="!claimLoading && claimPrep" class="space-y-4">
                                <p class="text-sm text-gray-600 bg-blue-50 rounded-lg p-3 border border-blue-200">💡 Sistem akan otomatis membuat <strong>LPJ SPPT/SPPD</strong>, <strong>Dokumen Word</strong>, dan <strong>Tiba Berangkat</strong>.</p>
                                <template x-if="claimPrep && claimPrep.is_reclaim && claimPrep.can_claim">
                                    <div class="bg-red-50 border border-red-300 rounded-lg p-4 text-sm text-red-800">
                                        <strong>⚠️ Klaim Ulang!</strong> Data lama (LPJ, Word, Tiba Berangkat) akan <strong>dihapus permanen</strong> lalu dibuat ulang.
                                    </div>
                                </template>
                                <template x-if="claimPrep && !claimPrep.can_claim"><div class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-700">⚠️ <span x-text="claimPrep.reason"></span></div></template>
                                <template x-if="claimPrep && claimPrep.can_claim">
                                    <div class="space-y-3">
                                        <template x-if="claimPrep.target_darat > 0">
                                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                                <h5 class="text-sm font-semibold text-blue-800 mb-1"><span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-bold">SPPT</span> Desa Darat (<span x-text="claimPrep.target_darat"></span> desa)</h5>
                                                <template x-if="claimPrep.auto_desa_darat"><p class="text-sm text-blue-900 font-medium">✅ <span x-text="claimPrep.auto_desa_darat"></span></p></template>
                                                <template x-if="!claimPrep.auto_desa_darat"><p class="text-xs text-amber-700 bg-amber-50 rounded p-2 border border-amber-200">⚠️ Jumlah tidak sesuai — desa dikosongkan.</p></template>
                                            </div>
                                        </template>
                                        <template x-if="claimPrep.target_seberang > 0">
                                            <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                                                <h5 class="text-sm font-semibold text-purple-800 mb-1"><span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded text-xs font-bold">SPPD</span> Desa Seberang (<span x-text="claimPrep.target_seberang"></span> desa)</h5>
                                                <template x-if="claimPrep.auto_desa_seberang"><p class="text-sm text-purple-900 font-medium">✅ <span x-text="claimPrep.auto_desa_seberang"></span></p></template>
                                                <template x-if="!claimPrep.auto_desa_seberang"><p class="text-xs text-amber-700 bg-amber-50 rounded p-2 border border-amber-200">⚠️ Jumlah tidak sesuai — desa dikosongkan.</p></template>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Success Step --}}
                        <div x-show="claimStep === 'success'" class="px-6 py-8 text-center">
                            <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="mt-3 text-lg font-bold text-gray-900">LPJ Berhasil Dibuat!</h3>

                            {{-- Download Buttons --}}
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
                                <button @click="closeModal(); location.reload()" class="px-5 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Tutup</button>
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div x-show="modalData && !modalLoading && claimStep !== 'success'" class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                @if($isAdmin)
                                    <a :href="modalData ? '{{ route('poa.index') }}/' + modalData.poa_id : '#'" class="text-sm text-blue-600 hover:underline font-medium" x-show="claimStep === 'detail' && modalData && modalData.poa_id">Lihat Detail POA →</a>
                                @endif
                                <button x-show="claimStep === 'form'" @click="claimStep = 'detail'" class="text-sm text-gray-600 hover:underline font-medium">← Kembali</button>
                            </div>
                            <div class="flex items-center gap-2">
                                <button x-show="claimStep === 'detail' && modalData" @click="startClaim()"
                                    :class="modalData && modalData.claimed ? 'bg-amber-600 hover:bg-amber-700' : 'bg-green-600 hover:bg-green-700'"
                                    class="px-4 py-2 text-sm font-medium text-white rounded-lg shadow-sm transition-colors flex items-center gap-1.5">
                                    <span x-text="modalData && modalData.claimed ? 'Klaim Ulang LPJ' : 'Buat LPJ'"></span>
                                </button>
                                <button x-show="claimStep === 'form' && claimPrep && claimPrep.can_claim && (claimPrep.target_darat > 0 || claimPrep.target_seberang > 0)"
                                    @click="submitClaim()" :disabled="claimSubmitting"
                                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 shadow-sm transition-colors flex items-center gap-1.5 disabled:opacity-50">
                                    <svg x-show="claimSubmitting" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span x-text="claimSubmitting ? 'Memproses...' : (claimPrep && claimPrep.is_reclaim ? 'Hapus Lama & Buat Ulang' : 'Buat LPJ & Tiba Berangkat')"></span>
                                </button>
                                <button @click="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════ --}}
            {{-- ADMIN SECTION — Stats, Charts, Tables (admin only)    --}}
            {{-- ═══════════════════════════════════════════════════════ --}}
            @if($isAdmin)

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                {{-- Total Pegawai --}}
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-users text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <dt class="text-xs font-medium text-gray-500 truncate">Pegawai</dt>
                                <dd class="text-xl font-bold text-gray-900">{{ number_format($totalEmployees) }}</dd>
                                <dd class="text-[10px] text-green-600 font-medium">{{ number_format($activeEmployees) }} aktif</dd>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Desa --}}
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <dt class="text-xs font-medium text-gray-500 truncate">Desa</dt>
                                <dd class="text-xl font-bold text-gray-900">{{ number_format($totalVillages) }}</dd>
                                <dd class="text-[10px] text-gray-500">Wilayah kerja</dd>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total LPJ --}}
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-file-alt text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <dt class="text-xs font-medium text-gray-500 truncate">LPJ</dt>
                                <dd class="text-xl font-bold text-gray-900">{{ number_format($totalLpjs) }}</dd>
                                <dd class="text-[10px] text-purple-600 font-medium">{{ number_format($monthlyLpjs) }} periode ini</dd>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Anggaran --}}
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-coins text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <dt class="text-xs font-medium text-gray-500 truncate">Anggaran</dt>
                                <dd class="text-lg font-bold text-gray-900">Rp {{ number_format($totalBudget, 0, ',', '.') }}</dd>
                                <dd class="text-[10px] text-orange-600 font-medium">Rp {{ number_format($monthlyBudget, 0, ',', '.') }} periode ini</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts and Analytics --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                {{-- Budget Trend Chart --}}
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-gray-900">Trend Anggaran</h3>
                        <div class="text-xs text-gray-500"><i class="fas fa-chart-line mr-1"></i>6 Bulan Dinamis</div>
                    </div>
                    <div class="h-52">
                        <canvas id="budgetChart" class="w-full h-full"></canvas>
                    </div>
                </div>

                {{-- LPJ by Type --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5">
                    <h3 class="text-base font-bold text-gray-900 mb-4">LPJ per Tipe</h3>
                    <div class="space-y-3">
                        @foreach($lpjByType as $type => $count)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full {{ $type == 'SPPT' ? 'bg-green-500' : 'bg-blue-500' }} mr-2"></div>
                                <span class="font-medium text-gray-700 text-sm">{{ $type }}</span>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-gray-900 text-sm">{{ $count }}</div>
                                <div class="text-[10px] text-gray-500">Rp {{ number_format($budgetByType[$type] ?? 0, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Breakdown --}}
                    <div class="mt-4 pt-4 border-t border-gray-100 space-y-3">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs text-gray-600">Transport</span>
                                <span class="text-xs font-bold text-blue-600">Rp {{ number_format($transportTotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $totalBudget > 0 ? ($transportTotal / $totalBudget) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs text-gray-600">Uang Harian</span>
                                <span class="text-xs font-bold text-green-600">Rp {{ number_format($perDiemTotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-green-600 h-1.5 rounded-full" style="width: {{ $totalBudget > 0 ? ($perDiemTotal / $totalBudget) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent LPJs --}}
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-5 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900">LPJ Terbaru</h3>
                    <a href="{{ route('lpjs.create') }}" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold py-1.5 px-4 rounded-lg transition duration-200 shadow-md text-sm">
                        <i class="fas fa-plus mr-1"></i>Buat LPJ
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Surat</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kegiatan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peserta</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anggaran</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($recentLpjs as $lpj)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $lpj->no_surat }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $lpj->type == 'SPPT' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $lpj->type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900"><div class="max-w-xs truncate">{{ $lpj->kegiatan }}</div></td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $lpj->participant_count }} orang</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-green-600">Rp {{ number_format($lpj->total_budget, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $lpj->tanggal_surat }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <a href="{{ route('lpjs.show', $lpj) }}" class="text-indigo-600 hover:text-indigo-900 mr-2"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('lpjs.edit', $lpj) }}" class="text-yellow-600 hover:text-yellow-900"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500 text-sm">Belum ada LPJ yang dibuat</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @endif
        </div>
    </div>

    {{-- ═══ Styles ═══ --}}
    @push('styles')
    <style>
        .calendar-container { position: relative; }
        .calendar-container thead { position: sticky; top: 0; z-index: 10; }
        .calendar-container thead th { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .calendar-container tbody td:first-child { position: sticky; left: 0; background: #f9fafb; box-shadow: 2px 0 4px rgba(0,0,0,0.05); }

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

        .mine-tooltip { position: relative; }
        .mine-tooltip::after {
            content: 'Anda ditugaskan bulan ini';
            position: absolute; bottom: calc(100% + 6px); left: 50%; transform: translateX(-50%);
            background: #1e293b; color: white; font-size: 11px; padding: 4px 10px;
            border-radius: 6px; white-space: nowrap; opacity: 0; pointer-events: none;
            transition: opacity 0.2s ease; z-index: 30;
        }
        .mine-tooltip::before {
            content: ''; position: absolute; bottom: calc(100% + 2px); left: 50%; transform: translateX(-50%);
            border: 4px solid transparent; border-top-color: #1e293b; opacity: 0; pointer-events: none;
            transition: opacity 0.2s ease; z-index: 30;
        }
        .mine-tooltip:hover::after, .mine-tooltip:hover::before { opacity: 1; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .calendar-hint {
            animation: fadeInUp 0.6s ease-out 0.5s both;
        }

        @media print {
            .mine-glow-green, .mine-glow-yellow, .mine-glow-red { animation: none !important; }
            .calendar-hint { display: none; }
        }
    </style>
    @endpush

    {{-- ═══ Scripts ═══ --}}
    @push('scripts')
    <script>
        function dashboardCalendar() {
            return {
                allData: @json($calendarData),
                filteredData: [],
                searchQuery: '',
                selectedKomponen: '',

                // Modal state
                modalOpen: false,
                modalLoading: false,
                modalError: null,
                modalData: null,
                currentPoaId: null,
                currentMonth: null,

                // Claim state
                claimStep: 'detail',
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
                        const q = this.searchQuery.toLowerCase().trim();
                        result = result.filter(row =>
                            row.kegiatan.toLowerCase().includes(q) ||
                            row.komponen.toLowerCase().includes(q) ||
                            row.rincian_menu.toLowerCase().includes(q)
                        );
                    }
                    if (this.selectedKomponen !== '') {
                        result = result.filter(row => row.komponen === this.selectedKomponen);
                    }
                    result.sort((a, b) => {
                        const aMine = Object.values(a.months).some(m => m && m.is_mine);
                        const bMine = Object.values(b.months).some(m => m && m.is_mine);
                        if (aMine && !bMine) return -1;
                        if (!aMine && bMine) return 1;
                        return a.kegiatan.localeCompare(b.kegiatan);
                    });
                    this.filteredData = result;
                },

                toggleKomponen(k) {
                    this.selectedKomponen = this.selectedKomponen === k ? '' : k;
                    this.filterData();
                },

                clearFilters() {
                    this.searchQuery = '';
                    this.selectedKomponen = '';
                    this.filterData();
                },

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
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        if (!response.ok) throw new Error('Gagal memuat data');
                        const data = await response.json();
                        data.poa_id = poaId;
                        this.modalData = data;
                    } catch (error) {
                        this.modalError = error.message || 'Terjadi kesalahan.';
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

                async startClaim() {
                    this.claimStep = 'form';
                    this.claimLoading = true;
                    this.claimPrep = null;
                    try {
                        const response = await fetch(`/poa/${this.currentPoaId}/calendar-claim-prep/${this.currentMonth}`, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
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
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
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
                            alert('Gagal membuat LPJ.');
                        }
                    } catch (error) {
                        alert('Kesalahan jaringan: ' + error.message);
                    } finally {
                        this.claimSubmitting = false;
                    }
                },

                formatRupiah(amount) {
                    if (amount == null || amount === 0) return 'Rp 0';
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(amount));
                },

                getMonthClass(md) {
                    if (!md) return '';
                    const t = md.tahap, c = md.claimed, m = md.is_mine;
                    let b = '';
                    if (t === 1) b = c ? 'bg-green-500 text-white border-green-600 shadow-sm' : 'bg-green-100 text-green-700 border-green-400';
                    else if (t === 2) b = c ? 'bg-yellow-500 text-white border-yellow-600 shadow-sm' : 'bg-yellow-100 text-yellow-700 border-yellow-400';
                    else if (t === 3) b = c ? 'bg-red-500 text-white border-red-600 shadow-sm' : 'bg-red-100 text-red-700 border-red-400';
                    if (m && !c) {
                        const g = t === 1 ? 'mine-glow-green' : t === 2 ? 'mine-glow-yellow' : 'mine-glow-red';
                        b += ' border-[3px] ' + g;
                    }
                    return b;
                },

                getMobileMonthClass(md) {
                    if (!md) return '';
                    const t = md.tahap, c = md.claimed, m = md.is_mine;
                    let b = '';
                    if (t === 1) b = c ? 'bg-green-500 text-white' : 'bg-green-100 text-green-700 border-2 border-green-400';
                    else if (t === 2) b = c ? 'bg-yellow-500 text-white' : 'bg-yellow-100 text-yellow-700 border-2 border-yellow-400';
                    else if (t === 3) b = c ? 'bg-red-500 text-white' : 'bg-red-100 text-red-700 border-2 border-red-400';
                    if (m && !c) {
                        const g = t === 1 ? 'mine-glow-green' : t === 2 ? 'mine-glow-yellow' : 'mine-glow-red';
                        b += ' ring-2 ring-offset-1 ' + g;
                    } else if (m && c) {
                        b += ' ring-2 ring-offset-1 ring-white';
                    }
                    return b;
                }
            };
        }
    </script>
    @endpush

    @if($isAdmin)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('budgetChart')?.getContext('2d');
            if (!ctx) return;
            const chartData = @json($chartData);
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.map(item => item.month),
                    datasets: [{
                        label: 'Total Anggaran',
                        data: chartData.map(item => item.total),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3, fill: true, tension: 0.4,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 5
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) }
                        }
                    }
                }
            });
        });
    </script>
    @endif
</x-app-layout>