<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LPJ BOK Puskesmas') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="{{ asset('css/landing-animations.css') }}" rel="stylesheet">
</head>
<body class="antialiased overflow-x-hidden">
    <!-- Background with animated gradient -->
    <div class="fixed inset-0 gradient-animation -z-10"></div>
    <div class="fixed inset-0 bg-black/20 -z-10"></div>

    <!-- Announcement Popup -->
    <div id="announcementModal" class="fixed inset-0 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeAnnouncement()"></div>
            
            <!-- Modal content -->
            <div id="announcementContent" class="relative bg-white rounded-2xl max-w-md w-full shadow-2xl transform transition-all">
                <!-- Loading state -->
                <div id="announcementLoader" class="p-8 text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
                    <p class="mt-4 text-gray-600">Memuat pengumuman...</p>
                </div>
                
                <!-- Actual content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center justify-center pt-20 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left">
                    <div class="animate-fade-in-up">
                        <h1 class="text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                            Sistem
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500">
                                LPJ BOK
                            </span>
                            Puskesmas Rawat Inap Kabalsiang Benjuring
                        </h1>
                    </div>
                    
                    {{-- <div class="animate-fade-in-up delay-200">
                        <p class="text-xl text-white/90 mb-8 leading-relaxed">
                            Kelola Laporan Pertanggungjawaban Bantuan Operasional Kesehatan dengan mudah, 
                            efisien, dan terintegrasi. Solusi digital untuk administrasi keuangan Puskesmas Rawat Inap Kabalsiang Benjuring.
                        </p>
                    </div> --}}

                    <!-- Action Buttons -->
                    <div class="animate-fade-in-up delay-400">
                        @auth
                            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                <a href="{{ url('/dashboard') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition duration-300 btn-modern inline-flex items-center justify-center">
                                    <i class="fas fa-tachometer-alt mr-3"></i>Buka Dashboard
                                </a>
                                <a href="{{ route('lpjs.index') }}" class="bg-white/20 hover:bg-white/30 text-white px-8 py-4 rounded-xl font-semibold text-lg transition duration-300 btn-modern inline-flex items-center justify-center glass">
                                    <i class="fas fa-file-alt mr-3"></i>Kelola LPJ
                                </a>
                            </div>
                        @else
                            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                <a href="{{ route('login') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition duration-300 btn-modern inline-flex items-center justify-center">
                                    <i class="fas fa-sign-in-alt mr-3"></i>Masuk Sekarang
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-white/20 hover:bg-white/30 text-white px-8 py-4 rounded-xl font-semibold text-lg transition duration-300 btn-modern inline-flex items-center justify-center glass">
                                        <i class="fas fa-user-plus mr-3"></i>Daftar Gratis
                                    </a>
                                @endif
                            </div>
                        @endauth
                    </div>

                    <!-- Stats -->
                    <div class="animate-fade-in-up delay-600 mt-12 grid grid-cols-3 gap-8 text-center lg:text-left">
                        <div>
                            <div class="text-3xl font-bold text-white">100%</div>
                            <div class="text-white/70">Digital</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-white">24/7</div>
                            <div class="text-white/70">Akses</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-white">Mudah</div>
                            <div class="text-white/70">& Efektif</div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Dashboard Preview -->
                <div class="animate-fade-in-right delay-300 relative">
                    <div class="relative animate-float">
                        <!-- Main Dashboard Card -->
                        <div class="glass rounded-2xl p-6 shadow-2xl">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-white font-semibold text-lg">Dashboard LPJ BOK</h3>
                                <div class="flex space-x-2">
                                    <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                                    <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                </div>
                            </div>
                            
                            <!-- Stats Cards -->
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-blue-100 text-sm">Total LPJ</p>
                                            <p class="text-white font-bold text-xl">24</p>
                                        </div>
                                        <i class="fas fa-dollar-sign text-blue-200 text-2xl"></i>
                                    </div>
                                </div>
                                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-green-100 text-sm">Anggaran</p>
                                            <p class="text-white font-bold text-lg">45.2M</p>
                                        </div>
                                        <i class="fas fa-money-bill-wave text-green-200 text-2xl"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Chart Area -->
                            <div class="bg-white/10 rounded-xl p-4 mb-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-white font-medium">Trend Anggaran</h4>
                                    <i class="fas fa-chart-line text-white/60"></i>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <div class="w-16 text-white/70 text-sm">Jan</div>
                                        <div class="flex-1 bg-white/20 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-blue-400 to-purple-500 h-2 rounded-full w-3/4"></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-16 text-white/70 text-sm">Feb</div>
                                        <div class="flex-1 bg-white/20 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full w-5/6"></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-16 text-white/70 text-sm">Mar</div>
                                        <div class="flex-1 bg-white/20 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-purple-400 to-pink-500 h-2 rounded-full w-4/5"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-3">
                                <button class="flex-1 bg-gradient-to-r from-purple-500 to-pink-500 text-white py-2 px-4 rounded-lg font-medium text-sm">
                                    <i class="fas fa-plus mr-2"></i>Buat LPJ
                                </button>
                                <button class="flex-1 bg-white/20 text-white py-2 px-4 rounded-lg font-medium text-sm">
                                    <i class="fas fa-eye mr-2"></i>Lihat Semua
                                </button>
                            </div>
                        </div>

                        <!-- Floating Cards -->
                        <div class="absolute -top-6 -right-6 glass rounded-xl p-4 animate-pulse-slow">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white"></i>
                                </div>
                                <div>
                                    <p class="text-white font-medium text-sm">LPJ Approved</p>
                                    <p class="text-white/70 text-xs">2 menit lalu</p>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-4 -left-4 glass rounded-xl p-4 animate-pulse-slow delay-300">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-upload text-white"></i>
                                </div>
                                <div>
                                    <p class="text-white font-medium text-sm">Download Success</p>
                                    <p class="text-white/70 text-xs">5 menit lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    {{-- <section class="py-20 relative" id="features">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16 animate-fade-in-up">
                <h2 class="text-4xl font-bold text-white mb-4">
                    Fitur Unggulan
                </h2>
                <p class="text-xl text-white/80 max-w-2xl mx-auto">
                    Solusi lengkap untuk mengelola LPJ BOK dengan efisien dan akurat
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card glass rounded-2xl p-8 text-center animate-fade-in-up delay-100">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-coins text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-4">Kelola LPJ Digital</h3>
                    <p class="text-white/70 leading-relaxed">
                        Buat, edit, dan kelola LPJ BOK secara digital dengan template yang sudah terstruktur dan sesuai standar.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card glass rounded-2xl p-8 text-center animate-fade-in-up delay-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chart-bar text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-4">Dashboard Analytics</h3>
                    <p class="text-white/70 leading-relaxed">
                        Monitor keuangan dan statistik LPJ dengan dashboard yang informatif dan real-time.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card glass rounded-2xl p-8 text-center animate-fade-in-up delay-300">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-4">Manajemen Pegawai</h3>
                    <p class="text-white/70 leading-relaxed">
                        Kelola data pegawai dan tracking saldo pembayaran untuk setiap kegiatan LPJ.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card glass rounded-2xl p-8 text-center animate-fade-in-up delay-400">
                    <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-download text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-4">Export & Print</h3>
                    <p class="text-white/70 leading-relaxed">
                        Download LPJ dalam format PDF yang rapi dan siap untuk dicetak atau dikirim.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card glass rounded-2xl p-8 text-center animate-fade-in-up delay-500">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-4">Keamanan Data</h3>
                    <p class="text-white/70 leading-relaxed">
                        Data LPJ tersimpan aman dengan sistem autentikasi dan backup otomatis.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card glass rounded-2xl p-8 text-center animate-fade-in-up delay-600">
                    <div class="w-16 h-16 bg-gradient-to-r from-teal-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-mobile-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-4">Mobile Friendly</h3>
                    <p class="text-white/70 leading-relaxed">
                        Akses sistem dari mana saja dengan tampilan yang responsif di semua perangkat.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="glass rounded-3xl p-12 animate-fade-in-up">
                <h2 class="text-4xl font-bold text-white mb-6">
                    Siap Memulai?
                </h2>
                <p class="text-xl text-white/80 mb-8 max-w-2xl mx-auto">
                    Bergabunglah dengan sistem LPJ BOK digital dan rasakan kemudahan dalam mengelola laporan keuangan Puskesmas Anda.
                </p>
                
                @auth
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ url('/dashboard') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition duration-300 btn-modern inline-flex items-center justify-center">
                            <i class="fas fa-rocket mr-3"></i>Mulai Sekarang
                        </a>
                        <a href="{{ route('lpjs.create') }}" class="bg-white/20 hover:bg-white/30 text-white px-8 py-4 rounded-xl font-semibold text-lg transition duration-300 btn-modern inline-flex items-center justify-center glass">
                            <i class="fas fa-plus mr-3"></i>Buat LPJ Pertama
                        </a>
                    </div>
                @else
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition duration-300 btn-modern inline-flex items-center justify-center">
                            <i class="fas fa-rocket mr-3"></i>Daftar Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="bg-white/20 hover:bg-white/30 text-white px-8 py-4 rounded-xl font-semibold text-lg transition duration-300 btn-modern inline-flex items-center justify-center glass">
                            <i class="fas fa-sign-in-alt mr-3"></i>Sudah Punya Akun?
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass rounded-2xl p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Logo & Description -->
                    <div class="animate-fade-in-left">
                        <div class="flex items-center mb-4">
                            <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mr-3">
                                <i class="fas fa-dollar-sign text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">LPJ BOK</h3>
                                <p class="text-sm text-white/80">Financial Management</p>
                            </div>
                        </div>
                        <p class="text-white/70 leading-relaxed">
                            Sistem digital untuk mengelola Laporan Pertanggungjawaban Bantuan Operasional Kesehatan dengan mudah dan efisien.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div class="animate-fade-in-up delay-200">
                        <h4 class="text-lg font-semibold text-white mb-4">Menu Cepat</h4>
                        <ul class="space-y-2">
                            @auth
                                <li><a href="{{ url('/dashboard') }}" class="text-white/70 hover:text-white transition duration-300">Dashboard</a></li>
                                <li><a href="{{ route('lpjs.index') }}" class="text-white/70 hover:text-white transition duration-300">Kelola LPJ</a></li>
                                <li><a href="{{ route('employees.index') }}" class="text-white/70 hover:text-white transition duration-300">Data Pegawai</a></li>
                                <li><a href="{{ route('employee-saldo.index') }}" class="text-white/70 hover:text-white transition duration-300">Saldo Pegawai</a></li>
                            @else
                                <li><a href="{{ route('login') }}" class="text-white/70 hover:text-white transition duration-300">Masuk</a></li>
                                <li><a href="{{ route('register') }}" class="text-white/70 hover:text-white transition duration-300">Daftar</a></li>
                                <li><a href="#features" class="text-white/70 hover:text-white transition duration-300">Fitur</a></li>
                            @endauth
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div class="animate-fade-in-right delay-400">
                        <h4 class="text-lg font-semibold text-white mb-4">Kontak</h4>
                        <div class="space-y-3">
                            <div class="flex items-center text-white/70">
                                <i class="fas fa-hospital mr-3"></i>
                                <span>Puskesmas Indonesia</span>
                            </div>
                            <div class="flex items-center text-white/70">
                                <i class="fas fa-envelope mr-3"></i>
                                <span>admin@lpjbok.id</span>
                            </div>
                            <div class="flex items-center text-white/70">
                                <i class="fas fa-phone mr-3"></i>
                                <span>+62 21 1234 5678</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Bar -->
                <div class="border-t border-white/20 mt-8 pt-8 text-center">
                    <p class="text-white/60">
                        &copy; {{ date('Y') }} LPJ BOK Puskesmas. Dibuat dengan ❤️ untuk kesehatan Indonesia.
                    </p>
                </div>
            </div>
        </div>
    </footer> --}}

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="fixed bottom-8 right-8 w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full shadow-lg opacity-0 transition-all duration-300 btn-modern z-40">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- JavaScript untuk Pengumuman -->
    <script>
        // Check for announcements on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check if announcement was already shown in this session
            const announcementShown = sessionStorage.getItem('announcementShown');
            
            if (!announcementShown) {
                // Fetch active announcement
                fetch('/api/pengumuman/active')
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.id) {
                            showAnnouncement(data);
                        }
                    })
                    .catch(error => console.error('Error fetching announcement:', error));
            }
        });

        function showAnnouncement(announcement) {
            const modal = document.getElementById('announcementModal');
            const content = document.getElementById('announcementContent');
            
            // Create announcement HTML with dynamic theme color
            const announcementHTML = `
                <div class="relative overflow-hidden rounded-2xl">
                    <!-- Header with gradient background -->
                    <div class="relative p-6 pb-4" style="background: linear-gradient(135deg, ${announcement.warna_tema} 0%, ${adjustColor(announcement.warna_tema, -20)} 100%);">
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute -top-4 -right-4 w-24 h-24 rounded-full bg-white opacity-20"></div>
                            <div class="absolute -bottom-8 -left-8 w-32 h-32 rounded-full bg-white opacity-10"></div>
                        </div>
                        
                        <div class="relative">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    ${announcement.prioritas === 'high' ? 
                                        '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mb-2">Penting</span>' : 
                                        announcement.prioritas === 'medium' ? 
                                        '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mb-2">Informasi</span>' : 
                                        ''}
                                    <h3 class="text-xl font-bold text-white mt-1">${announcement.judul}</h3>
                                </div>
                                <button onclick="closeAnnouncement()" class="ml-4 text-white hover:text-gray-200 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6 bg-white">
                        <div class="text-gray-700 leading-relaxed whitespace-pre-wrap">${announcement.isi}</div>
                        
                        ${announcement.tanggal_selesai ? 
                            `<div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-500">
                                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Berlaku sampai: ${new Date(announcement.tanggal_selesai).toLocaleDateString('id-ID', { 
                                        day: 'numeric', 
                                        month: 'long', 
                                        year: 'numeric' 
                                    })}
                                </p>
                            </div>` : ''}
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button onclick="closeAnnouncement()" 
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors">
                                Tutup
                            </button>
                            <button onclick="closeAnnouncementAndDontShowAgain()" 
                                class="px-4 py-2 text-white rounded-lg font-medium transition-colors" 
                                style="background-color: ${announcement.warna_tema}; hover:background-color: ${adjustColor(announcement.warna_tema, -10)};">
                                Mengerti
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            // Replace content
            content.innerHTML = announcementHTML;
            
            // Show modal with animation
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('.bg-black').classList.add('opacity-50');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeAnnouncement() {
            const modal = document.getElementById('announcementModal');
            const content = document.getElementById('announcementContent');
            
            // Hide with animation
            modal.querySelector('.bg-black').classList.remove('opacity-50');
            content.classList.remove('scale-100', 'opacity-100');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function closeAnnouncementAndDontShowAgain() {
            // Set session storage to not show again in this session
            sessionStorage.setItem('announcementShown', 'true');
            closeAnnouncement();
        }

        // Helper function to adjust color brightness
        function adjustColor(color, amount) {
            const num = parseInt(color.replace('#', ''), 16);
            const r = Math.max(0, Math.min(255, (num >> 16) + amount));
            const g = Math.max(0, Math.min(255, ((num >> 8) & 0x00FF) + amount));
            const b = Math.max(0, Math.min(255, (num & 0x0000FF) + amount));
            return '#' + ((r << 16) | (g << 8) | b).toString(16).padStart(6, '0');
        }
    </script>

    <!-- JavaScript Scroll to Top -->
    {{-- <script>
        // Scroll to top functionality
        const scrollToTopBtn = document.getElementById('scrollToTop');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.style.opacity = '1';
                scrollToTopBtn.style.transform = 'translateY(0)';
            } else {
                scrollToTopBtn.style.opacity = '0';
                scrollToTopBtn.style.transform = 'translateY(10px)';
            }
        });
        
        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add scroll-based animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.feature-card').forEach(card => {
            observer.observe(card);
        });
    </script> --}}
</body>
</html>
