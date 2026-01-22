@extends('layouts.app')

@section('title', 'Klaster 4: P2P & Kesehatan Lingkungan - Puskesmas')

@section('content')
    {{-- Hero Banner with Background Image --}}
    <section class="pt-24 lg:pt-32 pb-12 lg:pb-16 relative overflow-hidden min-h-[300px] lg:min-h-[400px]">
        {{-- Background Image - Mobile --}}
        <div class="absolute inset-0 lg:hidden">
            <img src="{{ asset('images/klaster4-mobile.png') }}" alt="Klaster 4 Background"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-red-50/95 via-red-50/80 to-red-50/60"></div>
        </div>

        {{-- Background Image - Desktop --}}
        <div class="absolute inset-0 hidden lg:block">
            <img src="{{ asset('images/klaster4-desktop.png') }}" alt="Klaster 4 Background"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-red-50/95 via-red-50/70 to-transparent"></div>
        </div>

        {{-- Content --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <nav class="flex items-center gap-2 text-sm text-neutral-500 mb-6">
                <a href="/" class="hover:text-primary-600 transition-colors">Beranda</a>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <span class="text-neutral-900 font-medium">Klaster 4</span>
            </nav>
            <div class="flex items-start gap-6">
                <div
                    class="w-16 h-16 lg:w-20 lg:h-20 rounded-2xl bg-red-100/90 backdrop-blur-sm text-red-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                    <i data-lucide="shield-check" class="w-8 h-8 lg:w-10 lg:h-10"></i>
                </div>
                <div class="max-w-xl">
                    <h1 class="text-3xl lg:text-4xl font-bold text-neutral-900 mb-3 drop-shadow-sm">Klaster 4: P2P & Kesling
                    </h1>
                    <p class="text-lg text-neutral-700 leading-relaxed">
                        Penanggulangan Penyakit Menular, Surveilans, Kewaspadaan Dini, dan Kesehatan Lingkungan.
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
                        <h2 class="text-xl font-semibold text-neutral-900 mb-4">Tentang Klaster P2P & Kesling</h2>
                        <p class="text-neutral-600 leading-relaxed">
                            Klaster ini bertugas mencegah dan menghentikan penularan penyakit di masyarakat serta
                            mengusahakan lingkungan yang sehat melalui surveilans, kewaspadaan dini, respons cepat, dan
                            pengawasan kualitas lingkungan.
                        </p>
                    </div>

                    {{-- P2P --}}
                    <div class="card p-6 lg:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                                <i data-lucide="bug" class="w-5 h-5"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-neutral-900">Pencegahan Penyakit Menular</h2>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-3">
                            <div class="flex items-center gap-2 p-3 bg-neutral-50 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-primary-500 flex-shrink-0"></i>
                                <span class="text-sm text-neutral-700">Surveilans Penyakit Menular</span>
                            </div>
                            <div class="flex items-center gap-2 p-3 bg-neutral-50 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-primary-500 flex-shrink-0"></i>
                                <span class="text-sm text-neutral-700">TB Paru & DOTS</span>
                            </div>
                            <div class="flex items-center gap-2 p-3 bg-neutral-50 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-primary-500 flex-shrink-0"></i>
                                <span class="text-sm text-neutral-700">HIV/AIDS & IMS</span>
                            </div>
                            <div class="flex items-center gap-2 p-3 bg-neutral-50 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-primary-500 flex-shrink-0"></i>
                                <span class="text-sm text-neutral-700">Kusta</span>
                            </div>
                            <div class="flex items-center gap-2 p-3 bg-neutral-50 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-primary-500 flex-shrink-0"></i>
                                <span class="text-sm text-neutral-700">DBD & Malaria</span>
                            </div>
                            <div class="flex items-center gap-2 p-3 bg-neutral-50 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-primary-500 flex-shrink-0"></i>
                                <span class="text-sm text-neutral-700">Diare & Hepatitis</span>
                            </div>
                            <div class="flex items-center gap-2 p-3 bg-neutral-50 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-primary-500 flex-shrink-0"></i>
                                <span class="text-sm text-neutral-700">Imunisasi Dewasa</span>
                            </div>
                            <div class="flex items-center gap-2 p-3 bg-neutral-50 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-primary-500 flex-shrink-0"></i>
                                <span class="text-sm text-neutral-700">Kewaspadaan Dini KLB</span>
                            </div>
                        </div>
                    </div>

                    {{-- Kesling --}}
                    <div class="card p-6 lg:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                                <i data-lucide="leaf" class="w-5 h-5"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-neutral-900">Kesehatan Lingkungan</h2>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-3">
                            <div class="flex items-center gap-2 p-3 bg-neutral-50 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-500 flex-shrink-0"></i>
                                <span class="text-sm text-neutral-700">Pengawasan Air Bersih</span>
                            </div>
                            <div class="flex items-center gap-2 p-3 bg-neutral-50 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-500 flex-shrink-0"></i>
                                <span class="text-sm text-neutral-700">Inspeksi Sanitasi</span>
                            </div>
                            <div class="flex items-center gap-2 p-3 bg-neutral-50 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-500 flex-shrink-0"></i>
                                <span class="text-sm text-neutral-700">Pengelolaan Limbah</span>
                            </div>
                            <div class="flex items-center gap-2 p-3 bg-neutral-50 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-500 flex-shrink-0"></i>
                                <span class="text-sm text-neutral-700">Pengawasan TTU & TPM</span>
                            </div>
                            <div class="flex items-center gap-2 p-3 bg-neutral-50 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-500 flex-shrink-0"></i>
                                <span class="text-sm text-neutral-700">Pengendalian Vektor</span>
                            </div>
                            <div class="flex items-center gap-2 p-3 bg-neutral-50 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-500 flex-shrink-0"></i>
                                <span class="text-sm text-neutral-700">Promosi PHBS</span>
                            </div>
                            <div class="flex items-center gap-2 p-3 bg-neutral-50 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-500 flex-shrink-0"></i>
                                <span class="text-sm text-neutral-700">Klinik Sanitasi</span>
                            </div>
                            <div class="flex items-center gap-2 p-3 bg-neutral-50 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-500 flex-shrink-0"></i>
                                <span class="text-sm text-neutral-700">Desa/Kelurahan ODF</span>
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
                                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                                    <i data-lucide="user" class="w-5 h-5 text-red-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-neutral-500">PJ Klaster 4 (P2P & Kesling)</p>
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
                        <div class="flex gap-3">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-neutral-900 mb-1">Lapor KLB</h4>
                                <p class="text-sm text-neutral-600 mb-2">
                                    Segera laporkan jika terdapat kejadian luar biasa di wilayah Anda.
                                </p>
                                <a href="tel:119" class="btn btn-primary text-sm py-2">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                    Hubungi 119
                                </a>
                            </div>
                        </div>
                    </div>

                    @include('components.cluster-sidebar')
                </div>
            </div>
        </div>
    </section>
@endsection