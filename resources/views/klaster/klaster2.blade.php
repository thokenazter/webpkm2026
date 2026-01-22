@extends('layouts.app')

@section('title', 'Klaster 2: Ibu & Anak - Puskesmas')

@section('content')
    {{-- Hero Banner with Background Image --}}
    <section class="pt-24 lg:pt-32 pb-12 lg:pb-16 relative overflow-hidden min-h-[300px] lg:min-h-[400px]">
        {{-- Background Image - Mobile --}}
        <div class="absolute inset-0 lg:hidden">
            <img src="{{ asset('images/klaster2-mobile.png') }}" alt="Klaster 2 Background"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-pink-50/95 via-pink-50/80 to-pink-50/60"></div>
        </div>

        {{-- Background Image - Desktop --}}
        <div class="absolute inset-0 hidden lg:block">
            <img src="{{ asset('images/klaster2-desktop.png') }}" alt="Klaster 2 Background"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-pink-50/95 via-pink-50/70 to-transparent"></div>
        </div>

        {{-- Content --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <nav class="flex items-center gap-2 text-sm text-neutral-500 mb-6">
                <a href="/" class="hover:text-primary-600 transition-colors">Beranda</a>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <span class="text-neutral-900 font-medium">Klaster 2</span>
            </nav>
            <div class="flex items-start gap-6">
                <div
                    class="w-16 h-16 lg:w-20 lg:h-20 rounded-2xl bg-pink-100/90 backdrop-blur-sm text-pink-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                    <i data-lucide="baby" class="w-8 h-8 lg:w-10 lg:h-10"></i>
                </div>
                <div class="max-w-xl">
                    <h1 class="text-3xl lg:text-4xl font-bold text-neutral-900 mb-3 drop-shadow-sm">Klaster 2: Ibu & Anak
                    </h1>
                    <p class="text-lg text-neutral-700 leading-relaxed">
                        Pelayanan kesehatan komprehensif untuk ibu hamil, bersalin, nifas, serta bayi, balita, anak
                        prasekolah, usia sekolah, dan remaja.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Description --}}
                    <div class="card p-6 lg:p-8">
                        <h2 class="text-xl font-semibold text-neutral-900 mb-4">Tentang Klaster Ibu & Anak</h2>
                        <p class="text-neutral-600 leading-relaxed mb-4">
                            Klaster Ibu dan Anak berfokus pada pelayanan kesehatan sepanjang siklus kehidupan awal, mulai
                            dari masa kehamilan hingga usia remaja. Pelayanan ini bertujuan untuk memastikan tumbuh kembang
                            optimal dan mencegah komplikasi kesehatan.
                        </p>
                        <p class="text-neutral-600 leading-relaxed">
                            Program unggulan meliputi Kesehatan Ibu dan Anak (KIA), Imunisasi, Gizi, serta Manajemen Terpadu
                            Balita Sakit (MTBS) yang sesuai standar Kementerian Kesehatan.
                        </p>
                    </div>

                    {{-- Services List --}}
                    <div class="card p-6 lg:p-8">
                        <h2 class="text-xl font-semibold text-neutral-900 mb-6">Layanan dalam Klaster Ibu & Anak</h2>
                        <div class="space-y-4">
                            {{-- Kesehatan Ibu --}}
                            <div class="border border-neutral-200 rounded-xl overflow-hidden">
                                <div class="bg-pink-50 p-4 flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center">
                                        <i data-lucide="heart" class="w-5 h-5"></i>
                                    </div>
                                    <h3 class="font-semibold text-neutral-900">Kesehatan Ibu</h3>
                                </div>
                                <div class="p-4">
                                    <ul class="space-y-2">
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Pemeriksaan Kehamilan (ANC)
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Pelayanan Persalinan Normal
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Pelayanan Nifas
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Keluarga Berencana (KB)
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Kesehatan Reproduksi
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Kesehatan Anak --}}
                            <div class="border border-neutral-200 rounded-xl overflow-hidden">
                                <div class="bg-blue-50 p-4 flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                        <i data-lucide="baby" class="w-5 h-5"></i>
                                    </div>
                                    <h3 class="font-semibold text-neutral-900">Kesehatan Bayi & Balita</h3>
                                </div>
                                <div class="p-4">
                                    <ul class="space-y-2">
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Pemantauan Tumbuh Kembang
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Imunisasi Dasar Lengkap
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Stimulasi Deteksi Intervensi Dini (SDIDTK)
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            MTBS (Manajemen Terpadu Balita Sakit)
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Pemberian Vitamin A
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Anak Sekolah & Remaja --}}
                            <div class="border border-neutral-200 rounded-xl overflow-hidden">
                                <div class="bg-amber-50 p-4 flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                                        <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                                    </div>
                                    <h3 class="font-semibold text-neutral-900">Anak Sekolah & Remaja</h3>
                                </div>
                                <div class="p-4">
                                    <ul class="space-y-2">
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Usaha Kesehatan Sekolah (UKS)
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Pelayanan Kesehatan Peduli Remaja (PKPR)
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Skrining Kesehatan Anak Sekolah
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Konseling Kesehatan Remaja
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Gizi --}}
                            <div class="border border-neutral-200 rounded-xl overflow-hidden">
                                <div class="bg-green-50 p-4 flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                                        <i data-lucide="apple" class="w-5 h-5"></i>
                                    </div>
                                    <h3 class="font-semibold text-neutral-900">Pelayanan Gizi</h3>
                                </div>
                                <div class="p-4">
                                    <ul class="space-y-2">
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Konseling Gizi & ASI Eksklusif
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Pemberian Makanan Tambahan (PMT)
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Penanganan Stunting
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-primary-500"></i>
                                            Tablet Tambah Darah (TTD)
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Contact Info - PJ Klaster --}}
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4">Informasi Kontak</h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center">
                                    <i data-lucide="user" class="w-5 h-5 text-pink-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-neutral-500">PJ Klaster 2 (KIA)</p>
                                    <p class="font-medium text-neutral-900">-</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-neutral-100 flex items-center justify-center">
                                    <i data-lucide="phone" class="w-5 h-5 text-neutral-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-neutral-500">Telepon</p>
                                    <p class="font-medium text-neutral-900">(021) 123-4567</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Jadwal Pelayanan --}}
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4">Jadwal Pelayanan</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-neutral-500">ANC Terpadu</span>
                                <span class="font-medium text-neutral-900">Senin - Sabtu</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-neutral-500">Imunisasi</span>
                                <span class="font-medium text-neutral-900">Selasa & Kamis</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-neutral-500">Posyandu</span>
                                <span class="font-medium text-neutral-900">Sesuai Jadwal</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-neutral-500">KB</span>
                                <span class="font-medium text-neutral-900">Setiap Hari Kerja</span>
                            </div>
                        </div>
                    </div>

                    {{-- Info Box --}}
                    <div class="card p-6 bg-pink-50 border-pink-200">
                        <div class="flex gap-3">
                            <i data-lucide="info" class="w-5 h-5 text-pink-600 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-neutral-900 mb-1">Persyaratan</h4>
                                <ul class="text-sm text-neutral-600 space-y-1">
                                    <li>• KTP / KK</li>
                                    <li>• Kartu BPJS / Asuransi</li>
                                    <li>• Buku KIA (untuk ibu/anak)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Other Clusters --}}
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4">Klaster Lainnya</h3>
                        <div class="space-y-2">
                            <a href="/klaster-1"
                                class="flex items-center gap-3 p-3 rounded-lg hover:bg-neutral-50 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <i data-lucide="settings" class="w-4 h-4"></i>
                                </div>
                                <span class="text-sm font-medium text-neutral-700">Klaster 1: Manajemen</span>
                            </a>
                            <a href="/klaster-3"
                                class="flex items-center gap-3 p-3 rounded-lg hover:bg-neutral-50 transition-colors">
                                <div
                                    class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                                    <i data-lucide="users" class="w-4 h-4"></i>
                                </div>
                                <span class="text-sm font-medium text-neutral-700">Klaster 3: Dewasa & Lansia</span>
                            </a>
                            <a href="/klaster-4"
                                class="flex items-center gap-3 p-3 rounded-lg hover:bg-neutral-50 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                                </div>
                                <span class="text-sm font-medium text-neutral-700">Klaster 4: P2P & Kesling</span>
                            </a>
                            <a href="/lintas-klaster"
                                class="flex items-center gap-3 p-3 rounded-lg hover:bg-neutral-50 transition-colors">
                                <div
                                    class="w-8 h-8 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center">
                                    <i data-lucide="layers" class="w-4 h-4"></i>
                                </div>
                                <span class="text-sm font-medium text-neutral-700">Lintas Klaster</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection