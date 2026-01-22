@extends('layouts.app')

@section('title', 'Lintas Klaster - Puskesmas')

@section('content')
    {{-- Hero Banner with Background Image --}}
    <section class="pt-24 lg:pt-32 pb-12 lg:pb-16 relative overflow-hidden min-h-[300px] lg:min-h-[400px]">
        {{-- Background Image - Mobile --}}
        <div class="absolute inset-0 lg:hidden">
            <img src="{{ asset('images/lintasklaster-mobile.png') }}" alt="Lintas Klaster Background"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-primary-50/95 via-primary-50/80 to-primary-50/60"></div>
        </div>

        {{-- Background Image - Desktop --}}
        <div class="absolute inset-0 hidden lg:block">
            <img src="{{ asset('images/lintasklaster-desktop.png') }}" alt="Lintas Klaster Background"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-primary-50/95 via-primary-50/70 to-transparent"></div>
        </div>

        {{-- Content --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <nav class="flex items-center gap-2 text-sm text-neutral-500 mb-6">
                <a href="/" class="hover:text-primary-600 transition-colors">Beranda</a>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <span class="text-neutral-900 font-medium">Lintas Klaster</span>
            </nav>
            <div class="flex items-start gap-6">
                <div
                    class="w-16 h-16 lg:w-20 lg:h-20 rounded-2xl bg-primary-100/90 backdrop-blur-sm text-primary-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                    <i data-lucide="layers" class="w-8 h-8 lg:w-10 lg:h-10"></i>
                </div>
                <div class="max-w-xl">
                    <h1 class="text-3xl lg:text-4xl font-bold text-neutral-900 mb-3 drop-shadow-sm">Lintas Klaster</h1>
                    <p class="text-lg text-neutral-700 leading-relaxed">
                        Layanan pendukung yang bersifat lintas klaster untuk memastikan pelayanan kesehatan yang
                        terintegrasi dan komprehensif.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    {{-- UGD --}}
                    <div class="card p-6 lg:p-8 border-l-4 border-l-red-500">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-14 h-14 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="siren" class="w-7 h-7"></i>
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-center gap-2 mb-2">
                                    <h2 class="text-xl font-semibold text-neutral-900">Unit Gawat Darurat (UGD)</h2>
                                    <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-semibold rounded-full">24
                                        JAM</span>
                                </div>
                                <p class="text-neutral-600 mb-4">
                                    Pelayanan kegawatdaruratan untuk kondisi yang membutuhkan penanganan segera. Dilengkapi
                                    dengan peralatan resusitasi dan tenaga terlatih.
                                </p>
                                <div class="grid sm:grid-cols-2 gap-2">
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-red-500"></i>
                                        Penanganan Kecelakaan
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-red-500"></i>
                                        Pertolongan Pertama
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-red-500"></i>
                                        Stabilisasi Pasien
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-red-500"></i>
                                        Rujukan Darurat
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Farmasi --}}
                    <div class="card p-6 lg:p-8 border-l-4 border-l-blue-500">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-14 h-14 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="pill" class="w-7 h-7"></i>
                            </div>
                            <div class="flex-grow">
                                <h2 class="text-xl font-semibold text-neutral-900 mb-2">Pelayanan Kefarmasian</h2>
                                <p class="text-neutral-600 mb-4">
                                    Penyediaan obat-obatan sesuai formularium, konseling penggunaan obat, dan pemantauan
                                    terapi obat untuk memastikan keamanan dan efektivitas pengobatan.
                                </p>
                                <div class="grid sm:grid-cols-2 gap-2">
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-blue-500"></i>
                                        Dispensing Obat
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-blue-500"></i>
                                        Konseling Obat
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-blue-500"></i>
                                        Informasi Obat
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-blue-500"></i>
                                        Pengelolaan Obat
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Laboratorium --}}
                    <div class="card p-6 lg:p-8 border-l-4 border-l-purple-500">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-14 h-14 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="flask-conical" class="w-7 h-7"></i>
                            </div>
                            <div class="flex-grow">
                                <h2 class="text-xl font-semibold text-neutral-900 mb-2">Laboratorium Klinik</h2>
                                <p class="text-neutral-600 mb-4">
                                    Pemeriksaan laboratorium untuk mendukung diagnosis dan pemantauan kondisi kesehatan
                                    pasien.
                                </p>
                                <div class="grid sm:grid-cols-2 gap-2">
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-purple-500"></i>
                                        Hematologi
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-purple-500"></i>
                                        Kimia Darah
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-purple-500"></i>
                                        Urinalisis
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-purple-500"></i>
                                        Gula Darah
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-purple-500"></i>
                                        Kolesterol
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-purple-500"></i>
                                        Tes Kehamilan
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-purple-500"></i>
                                        Mikrobiologi
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-purple-500"></i>
                                        Serologi
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Rawat Inap --}}
                    <div class="card p-6 lg:p-8 border-l-4 border-l-green-500">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-14 h-14 rounded-xl bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="bed" class="w-7 h-7"></i>
                            </div>
                            <div class="flex-grow">
                                <h2 class="text-xl font-semibold text-neutral-900 mb-2">Rawat Inap</h2>
                                <p class="text-neutral-600 mb-4">
                                    Fasilitas perawatan untuk pasien yang memerlukan observasi dan perawatan lebih lanjut
                                    dengan kapasitas terbatas.
                                </p>
                                <div class="grid sm:grid-cols-2 gap-2">
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-green-500"></i>
                                        Perawatan Umum
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-green-500"></i>
                                        Perawatan Persalinan
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-green-500"></i>
                                        Observasi Pasien
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-neutral-600">
                                        <i data-lucide="check" class="w-4 h-4 text-green-500"></i>
                                        Perawatan Paska Tindakan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- Contact Info - PJ Lintas Klaster --}}
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4">Informasi Kontak</h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center">
                                    <i data-lucide="user" class="w-5 h-5 text-primary-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-neutral-500">PJ Lintas Klaster</p>
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

                    <div class="card p-6 bg-red-50 border-red-200">
                        <h3 class="font-semibold text-neutral-900 mb-3 flex items-center gap-2">
                            <i data-lucide="phone-call" class="w-5 h-5 text-red-600"></i>
                            Hotline Darurat
                        </h3>
                        <a href="tel:119" class="block text-3xl font-bold text-red-600 mb-2">119</a>
                        <p class="text-sm text-neutral-600">Layanan darurat 24 jam</p>
                    </div>

                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4">Jam Layanan</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-neutral-500">UGD</span>
                                <span class="font-medium text-red-600">24 Jam</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-500">Farmasi</span>
                                <span class="font-medium">Sesuai Poli</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-500">Laboratorium</span>
                                <span class="font-medium">07:30 - 12:00</span>
                            </div>
                        </div>
                    </div>

                    @include('components.cluster-sidebar')
                </div>
            </div>
        </div>
    </section>
@endsection