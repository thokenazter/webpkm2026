@extends('pkm.layouts.app')

@section('title', 'Tim Layanan - Puskesmas Kabalsiang Benjuring')

@section('content')
    {{-- Hero Section --}}
    <section class="relative pt-24 pb-16 lg:pt-32 lg:pb-24 overflow-hidden">
        {{-- Background Gradient --}}
        <div class="absolute inset-0 gradient-hero-soft"></div>
        <div class="absolute top-0 right-0 w-1/2 h-full opacity-20">
            <div class="absolute top-20 right-20 w-72 h-72 bg-primary-200 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-40 w-56 h-56 bg-teal-200 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary-100 rounded-full mb-6">
                <span class="w-2 h-2 bg-primary-500 rounded-full animate-pulse"></span>
                <span class="text-sm font-medium text-primary-700">Integrasi Layanan Primer (ILP)</span>
            </div>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-neutral-900 mb-4">
                Tim Layanan Kesehatan
            </h1>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">
                Tenaga kesehatan profesional Puskesmas Rawat Inap Kabalsiang Benjuring
                yang siap melayani masyarakat
            </p>
        </div>
    </section>

    {{-- Statistics Section --}}
    <section class="relative -mt-8 lg:-mt-10 z-20 pb-4">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-neutral-200 px-6 py-5 lg:px-10 lg:py-6">
                <div class="grid grid-cols-3 divide-x divide-neutral-200">
                    <div class="text-center px-4">
                        <span class="text-3xl lg:text-4xl font-bold text-primary-600">{{ $totalStaff }}</span>
                        <p class="text-xs lg:text-sm text-neutral-500 mt-1">Total Tenaga Kesehatan</p>
                    </div>
                    <div class="text-center px-4">
                        <span class="text-3xl lg:text-4xl font-bold text-teal-600">{{ $clusterCount }}</span>
                        <p class="text-xs lg:text-sm text-neutral-500 mt-1">Klaster Layanan</p>
                    </div>
                    <div class="text-center px-4">
                        <span class="text-3xl lg:text-4xl font-bold text-amber-600">24</span>
                        <p class="text-xs lg:text-sm text-neutral-500 mt-1">Jam UGD & Rawat Inap</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Legal Narrative --}}
    <section class="py-8 lg:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-primary-50 border border-primary-100 rounded-2xl p-6 lg:p-8">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="scale" class="w-5 h-5 text-primary-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-primary-900 mb-2">Dasar Hukum Penugasan</h3>
                        <p class="text-sm text-primary-800 leading-relaxed">
                            Berdasarkan <strong>Permenkes No. 19 Tahun 2024</strong> tentang Integrasi Layanan Primer
                            (ILP) dan <strong>SK Kepala Puskesmas</strong> tentang pembagian tugas dan tanggung jawab
                            tenaga kesehatan di Puskesmas Rawat Inap Kabalsiang Benjuring.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Staff Profile Grid --}}
    <section class="py-8 lg:py-12" x-data="{ selectedStaff: null }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-bold text-neutral-900 mb-2">Profil Tenaga Kesehatan</h2>
                <p class="text-neutral-600 max-w-2xl mx-auto">
                    Klik pada card untuk melihat detail informasi dan tanggung jawab masing-masing tenaga kesehatan
                </p>
            </div>

            {{-- Staff Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 lg:gap-6">
                @foreach($allStaff as $index => $staff)
                    <div @click="selectedStaff = {{ $staff->id }}"
                        class="group bg-white rounded-2xl border border-neutral-200 overflow-hidden cursor-pointer
                               hover:shadow-lg hover:border-primary-300 hover:-translate-y-1 transition-all duration-300">
                        {{-- Photo --}}
                        <div class="relative aspect-[3/4] overflow-hidden bg-gradient-to-br from-primary-50 to-teal-50">
                            <img src="{{ $staff->photo_url }}" alt="{{ $staff->name }}"
                                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                                loading="lazy">
                            @if($staff->is_leader && $staff->cluster_id)
                                <div class="absolute top-2 right-2 bg-amber-400 text-amber-900 px-2 py-0.5 rounded-full text-xs font-bold shadow">
                                    ⭐ PJ
                                </div>
                            @elseif($staff->is_leader && !$staff->cluster_id)
                                <div class="absolute top-2 right-2 bg-primary-600 text-white px-2 py-0.5 rounded-full text-xs font-bold shadow">
                                    Pimpinan
                                </div>
                            @endif
                        </div>
                        {{-- Info --}}
                        <div class="p-3 lg:p-4">
                            <h3 class="text-sm lg:text-base font-semibold text-neutral-900 leading-tight line-clamp-2">
                                {{ $staff->name }}
                            </h3>
                            <p class="text-xs lg:text-sm text-neutral-500 mt-1 line-clamp-1">
                                {{ $staff->role }}
                            </p>
                            @if($staff->cluster)
                                <span class="inline-block mt-2 px-2 py-0.5 bg-primary-50 text-primary-700 text-xs rounded-full font-medium">
                                    {{ $staff->cluster->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Staff Detail Modal --}}
            <template x-teleport="body">
                <div x-show="selectedStaff !== null"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-[99] overflow-y-auto"
                    @keydown.escape.window="selectedStaff = null">

                    {{-- Backdrop --}}
                    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="selectedStaff = null"></div>

                    {{-- Modal Content --}}
                    <div class="relative min-h-screen flex items-center justify-center p-4">
                        <div x-show="selectedStaff !== null"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[85vh] overflow-hidden"
                            @click.stop>

                            {{-- Close Button --}}
                            <button @click="selectedStaff = null"
                                class="absolute top-4 right-4 z-10 w-9 h-9 bg-white/90 rounded-full flex items-center justify-center
                                       text-neutral-500 hover:text-red-500 hover:bg-white shadow-lg hover:shadow-xl transition-all duration-200 group">
                                <svg class="w-5 h-5 transition-transform duration-200 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>

                            {{-- Modal body for each staff --}}
                            @foreach($allStaff as $staff)
                                <div x-show="selectedStaff === {{ $staff->id }}" class="overflow-y-auto max-h-[85vh]">
                                    {{-- Header with photo, name, badges --}}
                                    <div class="relative bg-gradient-to-br from-primary-500 to-primary-600 px-6 pt-6 pb-6">
                                        <div class="flex items-center gap-4">
                                            <img src="{{ $staff->photo_url }}" alt="{{ $staff->name }}"
                                                class="w-20 h-20 rounded-2xl border-3 border-white/30 shadow-lg object-cover object-center flex-shrink-0">
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-lg font-bold text-white leading-tight">{{ $staff->name }}</h3>
                                                <p class="text-sm text-primary-100 font-medium mt-0.5">{{ $staff->role }}</p>
                                                <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                                    @if($staff->cluster)
                                                        <span class="px-2.5 py-0.5 bg-white/20 text-white text-xs rounded-full font-medium backdrop-blur-sm">
                                                            {{ $staff->cluster->name }}
                                                        </span>
                                                    @else
                                                        <span class="px-2.5 py-0.5 bg-white/20 text-white text-xs rounded-full font-medium backdrop-blur-sm">
                                                            Pimpinan
                                                        </span>
                                                    @endif
                                                    @if($staff->is_leader)
                                                        <span class="px-2.5 py-0.5 bg-amber-400/90 text-amber-900 text-xs rounded-full font-bold">
                                                            ⭐ {{ $staff->cluster_id ? 'PJ' : 'Kepala Puskesmas' }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Responsibilities --}}
                                    <div class="px-6 py-5 space-y-4">
                                        @if($staff->responsibilities->count() > 0)
                                            @php
                                                $grouped = $staff->responsibilities->groupBy('category');
                                                $categoryConfig = [
                                                    'admin_app' => ['label' => 'Admin Aplikasi', 'icon' => 'monitor', 'color' => 'blue'],
                                                    'koordinator' => ['label' => 'Koordinator', 'icon' => 'users', 'color' => 'purple'],
                                                    'laporan' => ['label' => 'Laporan SP2TP', 'icon' => 'file-text', 'color' => 'teal'],
                                                    'jaringan' => ['label' => 'Jaringan Pelayanan', 'icon' => 'network', 'color' => 'orange'],
                                                    'ruangan' => ['label' => 'PJ Ruangan', 'icon' => 'door-open', 'color' => 'rose'],
                                                    'program' => ['label' => 'Program', 'icon' => 'clipboard-list', 'color' => 'indigo'],
                                                ];
                                            @endphp

                                            <h4 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider">Tanggung Jawab</h4>

                                            @foreach($grouped as $category => $items)
                                                @php $config = $categoryConfig[$category] ?? ['label' => $category, 'icon' => 'circle', 'color' => 'neutral']; @endphp
                                                <div class="bg-neutral-50 rounded-xl p-4">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <i data-lucide="{{ $config['icon'] }}" class="w-4 h-4 text-{{ $config['color'] }}-500"></i>
                                                        <span class="text-sm font-semibold text-neutral-700">{{ $config['label'] }}</span>
                                                    </div>
                                                    <div class="flex flex-wrap gap-1.5">
                                                        @foreach($items as $item)
                                                            <span class="px-2.5 py-1 bg-white border border-neutral-200 text-neutral-700 text-xs rounded-lg font-medium shadow-sm">
                                                                {{ $item->title }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-6">
                                                <i data-lucide="info" class="w-8 h-8 text-neutral-300 mx-auto mb-2"></i>
                                                <p class="text-sm text-neutral-400">Belum ada data tanggung jawab tambahan</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </section>

    {{-- SK Penanggung Jawab Section --}}
    <section class="py-12 lg:py-16 bg-neutral-50" x-data="{ activeTab: 'admin' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-primary-100 text-primary-600 mb-4">
                    <i data-lucide="file-badge" class="w-7 h-7"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-neutral-900 mb-2">Penanggung Jawab & Koordinator</h2>
                <p class="text-neutral-600 max-w-2xl mx-auto">
                    Berdasarkan SK Kepala Puskesmas tentang pembagian tugas dan tanggung jawab tenaga kesehatan
                </p>
            </div>

            {{-- Tab Navigation --}}
            <div class="flex overflow-x-auto gap-2 pb-2 mb-6 scrollbar-hide justify-start lg:justify-center">
                <button @click="activeTab = 'admin'"
                    :class="activeTab === 'admin' ? 'bg-primary-600 text-white shadow-md' : 'bg-white text-neutral-600 hover:bg-neutral-100'"
                    class="px-4 py-2.5 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200 flex items-center gap-2 border border-neutral-200">
                    <i data-lucide="monitor" class="w-4 h-4"></i>
                    Admin Aplikasi
                </button>
                <button @click="activeTab = 'koordinator'"
                    :class="activeTab === 'koordinator' ? 'bg-primary-600 text-white shadow-md' : 'bg-white text-neutral-600 hover:bg-neutral-100'"
                    class="px-4 py-2.5 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200 flex items-center gap-2 border border-neutral-200">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    Koordinator
                </button>
                <button @click="activeTab = 'laporan'"
                    :class="activeTab === 'laporan' ? 'bg-primary-600 text-white shadow-md' : 'bg-white text-neutral-600 hover:bg-neutral-100'"
                    class="px-4 py-2.5 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200 flex items-center gap-2 border border-neutral-200">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    Laporan SP2TP
                </button>
                <button @click="activeTab = 'jaringan'"
                    :class="activeTab === 'jaringan' ? 'bg-primary-600 text-white shadow-md' : 'bg-white text-neutral-600 hover:bg-neutral-100'"
                    class="px-4 py-2.5 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200 flex items-center gap-2 border border-neutral-200">
                    <i data-lucide="network" class="w-4 h-4"></i>
                    Jaringan
                </button>
                <button @click="activeTab = 'ruangan'"
                    :class="activeTab === 'ruangan' ? 'bg-primary-600 text-white shadow-md' : 'bg-white text-neutral-600 hover:bg-neutral-100'"
                    class="px-4 py-2.5 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200 flex items-center gap-2 border border-neutral-200">
                    <i data-lucide="door-open" class="w-4 h-4"></i>
                    Ruangan
                </button>
                <button @click="activeTab = 'program'"
                    :class="activeTab === 'program' ? 'bg-primary-600 text-white shadow-md' : 'bg-white text-neutral-600 hover:bg-neutral-100'"
                    class="px-4 py-2.5 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200 flex items-center gap-2 border border-neutral-200">
                    <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                    Program
                </button>
            </div>

            {{-- Tab Content --}}
            <div class="bg-white rounded-2xl shadow-md border border-neutral-200 overflow-hidden">

                {{-- Tab: Admin Aplikasi --}}
                <div x-show="activeTab === 'admin'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-neutral-50 border-b border-neutral-200">
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3 w-8">No</th>
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Aplikasi / Sistem</th>
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Penanggung Jawab</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100">
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">1</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">P-Care</td><td class="px-6 py-3 text-sm text-neutral-600">Cindi Claudia Latusanay, A.Md.Kes</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">2</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">ASPAK</td><td class="px-6 py-3 text-sm text-neutral-600">Thobias Edwin Dasmaselah, S.KM</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">3</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">DFO</td><td class="px-6 py-3 text-sm text-neutral-600">Thobias Edwin Dasmaselah, S.KM</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">4</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">SISDMK</td><td class="px-6 py-3 text-sm text-neutral-600">Ns. Makdalena Ilely, S.Kep &bull; Thobias Edwin Dasmaselah, S.KM</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">5</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">INM & IKP</td><td class="px-6 py-3 text-sm text-neutral-600">Jacob Galandjindjinay, S.Kep.,Ns &bull; Kardioka Silaban, A.Md.Keb &bull; Nunuk Puspaningrum, A.Md.AK</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">6</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">E-Kohort, NPDM</td><td class="px-6 py-3 text-sm text-neutral-600">Kardioka Silaban, A.Md.Keb</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">7</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">SIMKESWA</td><td class="px-6 py-3 text-sm text-neutral-600">Jacob Galandjindjinay, S.Kep.,Ns</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">8</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">SISRUTE</td><td class="px-6 py-3 text-sm text-neutral-600">Rahima, A.Md.Keb</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">9</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">E-RENGGAR</td><td class="px-6 py-3 text-sm text-neutral-600">Ns. Makdalena Ilely, S.Kep &bull; Thobias Edwin Dasmaselah, S.KM</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">10</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">SIPD</td><td class="px-6 py-3 text-sm text-neutral-600">Ns. Makdalena Ilely, S.Kep &bull; Thobias Edwin Dasmaselah, S.KM &bull; Cindi Claudia Latusanay, A.Md.Kes</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">11</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">BNI Direct</td><td class="px-6 py-3 text-sm text-neutral-600">Ns. Makdalena Ilely, S.Kep &bull; Thobias Edwin Dasmaselah, S.KM</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">12</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">KRISNA</td><td class="px-6 py-3 text-sm text-neutral-600">Ns. Makdalena Ilely, S.Kep &bull; Thobias Edwin Dasmaselah, S.KM</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">13</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">RENBUT</td><td class="px-6 py-3 text-sm text-neutral-600">Ns. Makdalena Ilely, S.Kep &bull; Thobias Edwin Dasmaselah, S.KM</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">14</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">HFIS</td><td class="px-6 py-3 text-sm text-neutral-600">Cindi Claudia Latusanay, A.Md.Kes</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">15</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">MICROSITE</td><td class="px-6 py-3 text-sm text-neutral-600">Thobias Edwin Dasmaselah, S.KM</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">16</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">EPPGBM</td><td class="px-6 py-3 text-sm text-neutral-600">Gilyan Terri, A.Md.Gz</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">17</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">RME</td><td class="px-6 py-3 text-sm text-neutral-600">Irene Fordatkosu, S.KM</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab: Koordinator --}}
                <div x-show="activeTab === 'koordinator'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-neutral-50 border-b border-neutral-200">
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3 w-8">No</th>
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Jabatan Koordinator</th>
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Nama</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100">
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">1</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Bidan Koordinator</td><td class="px-6 py-3 text-sm text-neutral-600">Kardioka Silaban, A.Md.Keb</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">2</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Perawat Koordinator</td><td class="px-6 py-3 text-sm text-neutral-600">Ns. Makdalena Ilely, S.Kep</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">3</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Koordinator UKM</td><td class="px-6 py-3 text-sm text-neutral-600">Kardioka Silaban, A.Md.Keb</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">4</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Koordinator Admin</td><td class="px-6 py-3 text-sm text-neutral-600">Thobias Edwin Dasmaselah, S.KM</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">5</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Koordinator Jaringan</td><td class="px-6 py-3 text-sm text-neutral-600">Ns. Makdalena Ilely, S.Kep</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">6</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Koordinator SP2TP</td><td class="px-6 py-3 text-sm text-neutral-600">Cindi Claudia Latusanay, A.Md.Kes</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab: Laporan SP2TP --}}
                <div x-show="activeTab === 'laporan'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-neutral-50 border-b border-neutral-200">
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3 w-8">No</th>
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Jenis Laporan</th>
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Penanggung Jawab</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100">
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">1</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">LB1</td><td class="px-6 py-3 text-sm text-neutral-600">dr. Rahmatan</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">2</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">LB2</td><td class="px-6 py-3 text-sm text-neutral-600">Apt. Ardiansah, S.Farm</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">3</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">LB3</td><td class="px-6 py-3 text-sm text-neutral-600">Istika Sari Barend, A.Md.Keb</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">4</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">LB4</td><td class="px-6 py-3 text-sm text-neutral-600">Nunuk Puspaningrum, A.Md.AK</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">5</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">LB5</td><td class="px-6 py-3 text-sm text-neutral-600">Gilyan Terri, A.Md.Gz</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">6</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">10 Penyakit Terbesar</td><td class="px-6 py-3 text-sm text-neutral-600">dr. Rahmatan</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">7</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Rujukan</td><td class="px-6 py-3 text-sm text-neutral-600">Rahima, A.Md.Keb &bull; Amos N. Djabutafuan, S.Kep.,Ns</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">8</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Klem BPJS</td><td class="px-6 py-3 text-sm text-neutral-600">Cindi Claudia Latusanay, A.Md.Kes</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab: Jaringan Pelayanan --}}
                <div x-show="activeTab === 'jaringan'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-neutral-50 border-b border-neutral-200">
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3 w-8">No</th>
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Jaringan Pelayanan</th>
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Penanggung Jawab</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100">
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">1</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">POD Kompane</td><td class="px-6 py-3 text-sm text-neutral-500 italic">Belum ditetapkan</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">2</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Pustu Kumul</td><td class="px-6 py-3 text-sm text-neutral-600">Since Korsen, A.Md.Kep</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab: Ruangan --}}
                <div x-show="activeTab === 'ruangan'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-neutral-50 border-b border-neutral-200">
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3 w-8">No</th>
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Ruangan</th>
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Penanggung Jawab</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100">
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">1</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Klaster 1 Manajemen</td><td class="px-6 py-3 text-sm text-neutral-600">Onalin E.E. Habibuw, S.Kep., Ners</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">2</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Ruangan Pemeriksaan Klaster 1 dan 2</td><td class="px-6 py-3 text-sm text-neutral-600">dr. Rahmatan</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">3</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Klaster 2 KIA</td><td class="px-6 py-3 text-sm text-neutral-600">Kardioka Silaban, A.Md.Keb</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">4</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Klaster 3 Dewasa & Lansia</td><td class="px-6 py-3 text-sm text-neutral-600">Ns. Makdalena Ilely, S.Kep</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">5</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Klaster 4 P2P dan Kesling</td><td class="px-6 py-3 text-sm text-neutral-600">Jacob Galandjindjinay, S.Kep.,Ns</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">6</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Klaster 5 IGD</td><td class="px-6 py-3 text-sm text-neutral-600">Amos N. Djabutafuan, S.Kep.,Ns</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">7</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Klaster 5 Laboratorium</td><td class="px-6 py-3 text-sm text-neutral-600">Nunuk Puspaningrum, A.Md.AK</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">8</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Klaster 5 Apotik dan Gudang Obat</td><td class="px-6 py-3 text-sm text-neutral-600">Irene Ngarbinan, A.Md.Kep</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">9</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Gudang</td><td class="px-6 py-3 text-sm text-neutral-600">Yolanda Boger, A.Md.Kep</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">10</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Klaster 5 Persalinan</td><td class="px-6 py-3 text-sm text-neutral-600">Waode Kurniati Jan Jan, A.Md.Keb</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">11</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Ruangan Kepala Puskesmas</td><td class="px-6 py-3 text-sm text-neutral-600">Ns. Makdalena Ilely, S.Kep</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">12</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Auditorium</td><td class="px-6 py-3 text-sm text-neutral-600">Irene Fordatkosu, S.KM</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab: Program --}}
                <div x-show="activeTab === 'program'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-neutral-50 border-b border-neutral-200">
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3 w-8">No</th>
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Program</th>
                                    <th class="text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider px-6 py-3">Penanggung Jawab</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100">
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">1</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">KIA</td><td class="px-6 py-3 text-sm text-neutral-600">Kardioka Silaban, A.Md.Keb</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">2</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">KB</td><td class="px-6 py-3 text-sm text-neutral-600">Istika Sari Barend, A.Md.Keb</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">3</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">PosRem</td><td class="px-6 py-3 text-sm text-neutral-600">Waode Kurniati Jan Jan, A.Md.Keb</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">4</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Hepatitis</td><td class="px-6 py-3 text-sm text-neutral-600">Nunuk Puspaningrum, A.Md.AK</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">5</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">HIV</td><td class="px-6 py-3 text-sm text-neutral-600">Nunuk Puspaningrum, A.Md.AK</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">6</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">TB</td><td class="px-6 py-3 text-sm text-neutral-600">Nunuk Puspaningrum, A.Md.AK</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">7</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Tumbang</td><td class="px-6 py-3 text-sm text-neutral-600">Waode Kurniati Jan Jan, A.Md.Keb</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">8</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">MTBS</td><td class="px-6 py-3 text-sm text-neutral-600">Waode Kurniati Jan Jan, A.Md.Keb</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">9</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Gizi</td><td class="px-6 py-3 text-sm text-neutral-600">Gilyan Terri, A.Md.Gz</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">10</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Imunisasi</td><td class="px-6 py-3 text-sm text-neutral-600">Rahima, A.Md.Keb &bull; Irene Fordatkosu, S.KM</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">11</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Promkes</td><td class="px-6 py-3 text-sm text-neutral-600">Thobias Edwin Dasmaselah, S.KM</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">12</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">UKS</td><td class="px-6 py-3 text-sm text-neutral-600">Onalin E.E. Habibuw, S.Kep., Ners</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">13</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">KesWa</td><td class="px-6 py-3 text-sm text-neutral-600">Jacob Galandjindjinay, S.Kep., Ners</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">14</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Kesling</td><td class="px-6 py-3 text-sm text-neutral-600">Cindi Claudia Latusanay, A.Md.Kes</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">15</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">K3</td><td class="px-6 py-3 text-sm text-neutral-600">Cindi Claudia Latusanay, A.Md.Kes</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">16</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Kes. Lansia</td><td class="px-6 py-3 text-sm text-neutral-600">Amos N. Djabutafuan, S.Kep., Ners</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">17</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Kesorga</td><td class="px-6 py-3 text-sm text-neutral-600">Onalin E.E. Habibuw, S.Kep., Ners</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">18</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Malaria</td><td class="px-6 py-3 text-sm text-neutral-600">Yolanda Boger, A.Md.Kep &bull; Irene Ngarbinan, A.Md.Kep</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">19</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">ISPA & Diare</td><td class="px-6 py-3 text-sm text-neutral-600">Irene Ngarbinan, A.Md.Kep</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">20</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">PTM</td><td class="px-6 py-3 text-sm text-neutral-600">Ns. Makdalena Ilely, S.Kep &bull; Hetreda Ketno, S.Kep., Ners</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">21</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Surveilans</td><td class="px-6 py-3 text-sm text-neutral-600">Jacob Galandjindjinay, S.Kep., Ners</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">22</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">KUSTA</td><td class="px-6 py-3 text-sm text-neutral-600">Jacob Galandjindjinay, S.Kep., Ners</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">23</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">PerKesMas</td><td class="px-6 py-3 text-sm text-neutral-600">Amos N. Djabutafuan, S.Kep., Ners</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">24</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">TOGA</td><td class="px-6 py-3 text-sm text-neutral-600">Yolanda Boger, A.Md.Kep</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">25</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">POPM Kecacingan</td><td class="px-6 py-3 text-sm text-neutral-600">Irene Ngarbinan, A.Md.Kep</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">26</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">PAGHBTB</td><td class="px-6 py-3 text-sm text-neutral-600">Amos N. Djabutafuan, S.Kep., Ners</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">27</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Skrining BPJS</td><td class="px-6 py-3 text-sm text-neutral-600">Hetreda Ketno, S.Kep., Ners &bull; Irene Fordatkosu, S.KM</td></tr>
                                <tr class="hover:bg-neutral-50 transition-colors"><td class="px-6 py-3 text-sm text-neutral-400">28</td><td class="px-6 py-3 text-sm font-medium text-neutral-900">Pustu Kumul</td><td class="px-6 py-3 text-sm text-neutral-600">Margareta Mangar, A.Md.Keb</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-16 lg:py-20 bg-gradient-to-br from-primary-600 to-primary-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">Butuh Informasi Lebih Lanjut?</h2>
            <p class="text-primary-100 mb-8 max-w-2xl mx-auto">
                Hubungi kami untuk informasi layanan kesehatan atau kunjungi Puskesmas Kabalsiang Benjuring
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('pkm.hubungi-kami') }}" class="btn bg-white text-primary-700 hover:bg-primary-50 px-6 py-3">
                    <i data-lucide="phone" class="w-5 h-5"></i>
                    Hubungi Kami
                </a>
                <a href="{{ url('/') }}" class="btn border-2 border-white text-white hover:bg-white/10 px-6 py-3">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </section>
@endsection
