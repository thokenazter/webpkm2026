@extends('layouts.app')

@section('title', 'Struktur Organisasi - Puskesmas Kabalsiang Benjuring')

@section('content')
    {{-- Hero Banner --}}
    <section class="pt-24 lg:pt-32 pb-12 lg:pb-16 bg-gradient-to-br from-primary-50 to-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/2 h-full opacity-30">
            <div class="absolute top-20 right-20 w-72 h-72 bg-primary-200 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <nav class="flex items-center gap-2 text-sm text-neutral-500 mb-6">
                <a href="/" class="hover:text-primary-600 transition-colors">Beranda</a>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <span class="text-neutral-900 font-medium">Struktur Organisasi</span>
            </nav>
            <div class="max-w-3xl">
                <h1 class="text-3xl lg:text-4xl font-bold text-neutral-900 mb-4">Struktur Organisasi</h1>
                <p class="text-lg text-neutral-600">
                    Struktur organisasi Puskesmas berdasarkan <strong class="text-primary-700">Permenkes Nomor 19 Tahun
                        2024</strong>
                    mengadopsi sistem klaster (pelayanan terintegrasi) berdasarkan siklus hidup dalam kerangka
                    <strong class="text-primary-700">Integrasi Layanan Primer (ILP)</strong>.
                </p>
            </div>
        </div>
    </section>

    {{-- Org Structure Visual --}}
    <section class="py-12 lg:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Kepala Puskesmas --}}
            <div class="flex justify-center mb-8">
                <div
                    class="card p-6 text-center border-2 border-primary-500 bg-gradient-to-br from-primary-50 to-white shadow-lg max-w-sm w-full">
                    <div
                        class="w-16 h-16 mx-auto rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center mb-4 shadow-lg">
                        <i data-lucide="crown" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-neutral-900">Kepala Puskesmas</h3>
                    <p class="text-sm text-neutral-500 mt-1">Pemimpin & Penanggung Jawab Utama</p>
                    <div class="mt-3 px-3 py-1.5 bg-primary-100 rounded-full inline-block">
                        <span class="text-xs font-medium text-primary-700">dr. [Nama Kepala Puskesmas]</span>
                    </div>
                </div>
            </div>

            {{-- Connection Line --}}
            <div class="flex justify-center mb-8">
                <div class="w-0.5 h-12 bg-neutral-300"></div>
            </div>

            {{-- Klaster Grid --}}
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                {{-- Klaster 1: Manajemen --}}
                <div class="card p-6 border-l-4 border-l-blue-500 hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="settings" class="w-6 h-6"></i>
                        </div>
                        <div class="flex-grow">
                            <span class="text-xs font-medium text-blue-600 uppercase tracking-wide">Klaster 1</span>
                            <h3 class="text-lg font-semibold text-neutral-900 mt-1">Manajemen</h3>
                            <p class="text-sm text-neutral-500 mt-2">Menggantikan fungsi Tata Usaha</p>
                            <ul class="mt-3 space-y-1.5 text-sm text-neutral-600">
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-blue-500"></i>
                                    Administrasi & Kepegawaian
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-blue-500"></i>
                                    Keuangan & Aset
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-blue-500"></i>
                                    Mutu & Akreditasi
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-blue-500"></i>
                                    Sistem Informasi
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-neutral-100">
                        <p class="text-xs text-neutral-400">Penanggung Jawab Klaster</p>
                        <p class="text-sm font-medium text-neutral-700">[Nama PJ Klaster 1]</p>
                    </div>
                </div>

                {{-- Klaster 2: KIA --}}
                <div class="card p-6 border-l-4 border-l-pink-500 hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="baby" class="w-6 h-6"></i>
                        </div>
                        <div class="flex-grow">
                            <span class="text-xs font-medium text-pink-600 uppercase tracking-wide">Klaster 2</span>
                            <h3 class="text-lg font-semibold text-neutral-900 mt-1">Kesehatan Ibu & Anak</h3>
                            <p class="text-sm text-neutral-500 mt-2">Siklus kehidupan awal</p>
                            <ul class="mt-3 space-y-1.5 text-sm text-neutral-600">
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-pink-500"></i>
                                    Ibu Hamil, Bersalin, Nifas
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-pink-500"></i>
                                    Bayi & Balita
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-pink-500"></i>
                                    Anak Prasekolah & Sekolah
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-pink-500"></i>
                                    Remaja
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-neutral-100">
                        <p class="text-xs text-neutral-400">Penanggung Jawab Klaster</p>
                        <p class="text-sm font-medium text-neutral-700">[Nama PJ Klaster 2]</p>
                    </div>
                </div>

                {{-- Klaster 3: Dewasa & Lansia --}}
                <div class="card p-6 border-l-4 border-l-amber-500 hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="users" class="w-6 h-6"></i>
                        </div>
                        <div class="flex-grow">
                            <span class="text-xs font-medium text-amber-600 uppercase tracking-wide">Klaster 3</span>
                            <h3 class="text-lg font-semibold text-neutral-900 mt-1">Dewasa & Lansia</h3>
                            <p class="text-sm text-neutral-500 mt-2">Usia produktif & lanjut usia</p>
                            <ul class="mt-3 space-y-1.5 text-sm text-neutral-600">
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-amber-500"></i>
                                    Dewasa (18-59 tahun)
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-amber-500"></i>
                                    Lansia (>59 tahun)
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-amber-500"></i>
                                    PTM & Kesehatan Jiwa
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-amber-500"></i>
                                    Pelayanan Gigi & Mulut
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-neutral-100">
                        <p class="text-xs text-neutral-400">Penanggung Jawab Klaster</p>
                        <p class="text-sm font-medium text-neutral-700">[Nama PJ Klaster 3]</p>
                    </div>
                </div>

                {{-- Klaster 4: P2P & Kesling --}}
                <div class="card p-6 border-l-4 border-l-red-500 hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                        <div class="flex-grow">
                            <span class="text-xs font-medium text-red-600 uppercase tracking-wide">Klaster 4</span>
                            <h3 class="text-lg font-semibold text-neutral-900 mt-1">P2P & Kesling</h3>
                            <p class="text-sm text-neutral-500 mt-2">Penanggulangan & Lingkungan</p>
                            <ul class="mt-3 space-y-1.5 text-sm text-neutral-600">
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-red-500"></i>
                                    Surveilans Penyakit
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-red-500"></i>
                                    Respon Krisis Kesehatan
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-red-500"></i>
                                    Kesehatan Lingkungan
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-red-500"></i>
                                    Pengendalian Vektor
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-neutral-100">
                        <p class="text-xs text-neutral-400">Penanggung Jawab Klaster</p>
                        <p class="text-sm font-medium text-neutral-700">[Nama PJ Klaster 4]</p>
                    </div>
                </div>

                {{-- Lintas Klaster --}}
                <div
                    class="card p-6 border-l-4 border-l-primary-500 hover:shadow-lg transition-shadow md:col-span-2 lg:col-span-2">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="layers" class="w-6 h-6"></i>
                        </div>
                        <div class="flex-grow">
                            <span class="text-xs font-medium text-primary-600 uppercase tracking-wide">Lintas Klaster</span>
                            <h3 class="text-lg font-semibold text-neutral-900 mt-1">Pelayanan Pendukung Terintegrasi</h3>
                            <p class="text-sm text-neutral-500 mt-2">Mendukung seluruh klaster dalam memberikan pelayanan
                                komprehensif</p>
                            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3 mt-4">
                                <div class="flex items-center gap-2 p-2 bg-primary-50 rounded-lg">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-primary-600"></i>
                                    <span class="text-sm text-neutral-700">Rawat Inap</span>
                                </div>
                                <div class="flex items-center gap-2 p-2 bg-primary-50 rounded-lg">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-primary-600"></i>
                                    <span class="text-sm text-neutral-700">UGD 24 Jam</span>
                                </div>
                                <div class="flex items-center gap-2 p-2 bg-primary-50 rounded-lg">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-primary-600"></i>
                                    <span class="text-sm text-neutral-700">Laboratorium</span>
                                </div>
                                <div class="flex items-center gap-2 p-2 bg-primary-50 rounded-lg">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-primary-600"></i>
                                    <span class="text-sm text-neutral-700">Farmasi/Apotek</span>
                                </div>
                                <div class="flex items-center gap-2 p-2 bg-primary-50 rounded-lg">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-primary-600"></i>
                                    <span class="text-sm text-neutral-700">Gizi Klinik</span>
                                </div>
                                <div class="flex items-center gap-2 p-2 bg-primary-50 rounded-lg">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-primary-600"></i>
                                    <span class="text-sm text-neutral-700">Rehabilitasi Medik</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-neutral-100">
                        <p class="text-xs text-neutral-400">Penanggung Jawab Lintas Klaster</p>
                        <p class="text-sm font-medium text-neutral-700">[Nama PJ Lintas Klaster]</p>
                    </div>
                </div>
            </div>

            {{-- Tim Manajemen --}}
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-neutral-900 mb-6 text-center">Tim Manajemen Puskesmas</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="card p-5 text-center hover:shadow-md transition-shadow">
                        <div
                            class="w-12 h-12 mx-auto rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-3">
                            <i data-lucide="map-pin" class="w-6 h-6"></i>
                        </div>
                        <h4 class="font-semibold text-neutral-900">Tim Pembina Wilayah</h4>
                        <p class="text-xs text-neutral-500 mt-1">Koordinasi pelayanan di wilayah kerja</p>
                    </div>
                    <div class="card p-5 text-center hover:shadow-md transition-shadow">
                        <div
                            class="w-12 h-12 mx-auto rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center mb-3">
                            <i data-lucide="home" class="w-6 h-6"></i>
                        </div>
                        <h4 class="font-semibold text-neutral-900">Tim Pembina Keluarga</h4>
                        <p class="text-xs text-neutral-500 mt-1">Program Kesehatan Keluarga (PIS-PK)</p>
                    </div>
                    <div class="card p-5 text-center hover:shadow-md transition-shadow">
                        <div
                            class="w-12 h-12 mx-auto rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mb-3">
                            <i data-lucide="award" class="w-6 h-6"></i>
                        </div>
                        <h4 class="font-semibold text-neutral-900">Tim Akreditasi</h4>
                        <p class="text-xs text-neutral-500 mt-1">Penjaminan mutu & akreditasi</p>
                    </div>
                    <div class="card p-5 text-center hover:shadow-md transition-shadow">
                        <div
                            class="w-12 h-12 mx-auto rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center mb-3">
                            <i data-lucide="database" class="w-6 h-6"></i>
                        </div>
                        <h4 class="font-semibold text-neutral-900">Tim Sistem Informasi</h4>
                        <p class="text-xs text-neutral-500 mt-1">Pengelolaan SIK & data kesehatan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Info Section --}}
    <section class="py-12 lg:py-16 bg-neutral-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card p-6 lg:p-8 bg-gradient-to-br from-primary-50 to-white border-primary-200">
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="info" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-neutral-900 mb-2">Tentang Struktur Klaster</h3>
                        <p class="text-neutral-600 leading-relaxed mb-4">
                            Struktur organisasi berbasis klaster sesuai <strong>Permenkes Nomor 19 Tahun 2024</strong>
                            menggantikan struktur unit fungsional konvensional. Sistem ini menghapus jabatan Kepala Sub
                            Bagian
                            Tata Usaha dan mengintegrasikan fungsinya ke dalam Klaster Manajemen.
                        </p>
                        <p class="text-neutral-600 leading-relaxed">
                            Setiap klaster dipimpin oleh <strong>Penanggung Jawab Klaster</strong> yang merupakan pejabat
                            fungsional yang ditunjuk, dibantu oleh pelaksana kegiatan sesuai kompetensi masing-masing.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection