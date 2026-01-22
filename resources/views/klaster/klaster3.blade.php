@extends('layouts.app')

@section('title', 'Klaster 3: Dewasa & Lansia - Puskesmas')

@section('content')
    {{-- Hero Banner with Background Image --}}
    <section class="pt-24 lg:pt-32 pb-12 lg:pb-16 relative overflow-hidden min-h-[300px] lg:min-h-[400px]">
        {{-- Background Image - Mobile --}}
        <div class="absolute inset-0 lg:hidden">
            <img src="{{ asset('images/klaster3-mobile.png') }}" alt="Klaster 3 Background"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-amber-50/95 via-amber-50/80 to-amber-50/60"></div>
        </div>

        {{-- Background Image - Desktop --}}
        <div class="absolute inset-0 hidden lg:block">
            <img src="{{ asset('images/klaster3-desktop.png') }}" alt="Klaster 3 Background"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-amber-50/95 via-amber-50/70 to-transparent"></div>
        </div>

        {{-- Content --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <nav class="flex items-center gap-2 text-sm text-neutral-500 mb-6">
                <a href="/" class="hover:text-primary-600 transition-colors">Beranda</a>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <span class="text-neutral-900 font-medium">Klaster 3</span>
            </nav>
            <div class="flex items-start gap-6">
                <div
                    class="w-16 h-16 lg:w-20 lg:h-20 rounded-2xl bg-amber-100/90 backdrop-blur-sm text-amber-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                    <i data-lucide="users" class="w-8 h-8 lg:w-10 lg:h-10"></i>
                </div>
                <div class="max-w-xl">
                    <h1 class="text-3xl lg:text-4xl font-bold text-neutral-900 mb-3 drop-shadow-sm">Klaster 3: Dewasa &
                        Lansia</h1>
                    <p class="text-lg text-neutral-700 leading-relaxed">
                        Pelayanan kesehatan untuk masyarakat usia dewasa (18-59 tahun) dan lanjut usia (>59 tahun).
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
                    <div class="card p-6 lg:p-8">
                        <h2 class="text-xl font-semibold text-neutral-900 mb-4">Tentang Klaster Dewasa & Lansia</h2>
                        <p class="text-neutral-600 leading-relaxed mb-4">
                            Klaster ini menyediakan pelayanan komprehensif untuk masyarakat usia produktif dan lanjut usia
                            dengan fokus pada deteksi dini, pencegahan, dan pengelolaan Penyakit Tidak Menular (PTM).
                        </p>
                    </div>

                    <div class="card p-6 lg:p-8">
                        <h2 class="text-xl font-semibold text-neutral-900 mb-6">Layanan Tersedia</h2>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div
                                class="flex items-start gap-4 p-4 rounded-xl bg-neutral-50 hover:bg-red-50 transition-colors">
                                <div
                                    class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="activity" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-neutral-900">Skrining PTM</h3>
                                    <p class="text-sm text-neutral-500">Hipertensi, Diabetes, Kolesterol</p>
                                </div>
                            </div>
                            <div
                                class="flex items-start gap-4 p-4 rounded-xl bg-neutral-50 hover:bg-purple-50 transition-colors">
                                <div
                                    class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="brain" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-neutral-900">Kesehatan Jiwa</h3>
                                    <p class="text-sm text-neutral-500">Konseling & Deteksi Dini</p>
                                </div>
                            </div>
                            <div
                                class="flex items-start gap-4 p-4 rounded-xl bg-neutral-50 hover:bg-amber-50 transition-colors">
                                <div
                                    class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="heart-handshake" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-neutral-900">Posyandu Lansia</h3>
                                    <p class="text-sm text-neutral-500">Pemeriksaan Rutin Lansia</p>
                                </div>
                            </div>
                            <div
                                class="flex items-start gap-4 p-4 rounded-xl bg-neutral-50 hover:bg-blue-50 transition-colors">
                                <div
                                    class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="stethoscope" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-neutral-900">Poli Umum</h3>
                                    <p class="text-sm text-neutral-500">Pemeriksaan Kesehatan Umum</p>
                                </div>
                            </div>
                            <div
                                class="flex items-start gap-4 p-4 rounded-xl bg-neutral-50 hover:bg-cyan-50 transition-colors">
                                <div
                                    class="w-10 h-10 rounded-lg bg-cyan-100 text-cyan-600 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="smile" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-neutral-900">Poli Gigi</h3>
                                    <p class="text-sm text-neutral-500">Kesehatan Gigi & Mulut</p>
                                </div>
                            </div>
                            <div
                                class="flex items-start gap-4 p-4 rounded-xl bg-neutral-50 hover:bg-green-50 transition-colors">
                                <div
                                    class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-neutral-900">Kesehatan Indra</h3>
                                    <p class="text-sm text-neutral-500">Mata & Telinga</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- Contact Info - PJ Klaster --}}
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4">Informasi Kontak</h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                                    <i data-lucide="user" class="w-5 h-5 text-amber-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-neutral-500">PJ Klaster 3 (PTM & Lansia)</p>
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

                    {{-- Jadwal --}}
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4">Jadwal</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between"><span class="text-neutral-500">Poli Umum</span><span
                                    class="font-medium">Senin-Sabtu</span></div>
                            <div class="flex justify-between"><span class="text-neutral-500">Posyandu Lansia</span><span
                                    class="font-medium">Kamis</span></div>
                            <div class="flex justify-between"><span class="text-neutral-500">Posbindu PTM</span><span
                                    class="font-medium">Rabu & Jumat</span></div>
                        </div>
                    </div>

                    @include('components.cluster-sidebar')
                </div>
            </div>
        </div>
    </section>
@endsection