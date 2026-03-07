<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Layanan - Puskesmas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-primary-50 via-white to-accent-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-4xl">
        {{-- Welcome Header --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary-100 text-primary-600 mb-4">
                <i data-lucide="layout-dashboard" class="w-8 h-8"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-neutral-900 mb-2">
                Selamat Datang, {{ auth()->user()->name }}!
            </h1>
            <p class="text-neutral-500 text-sm sm:text-base">Pilih layanan yang ingin Anda akses</p>
        </div>

        {{-- Service Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 {{ auth()->user()->canManageContent() ? 'lg:grid-cols-3' : '' }} gap-6 max-w-3xl mx-auto">

            {{-- Card 1: Website PKM --}}
            <a href="/" class="group relative bg-white rounded-2xl border border-neutral-200 p-6 sm:p-8 shadow-sm hover:shadow-xl hover:border-primary-300 transition-all duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="globe" class="w-6 h-6"></i>
                </div>
                <h3 class="text-lg font-semibold text-neutral-900 mb-2">Website Puskesmas</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">
                    Kembali ke halaman website publik Puskesmas, beranda, berita, galeri, dan informasi klaster.
                </p>
                <div class="mt-4 flex items-center gap-1 text-primary-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Buka</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </a>

            {{-- Card 2: Dashboard BOK --}}
            <a href="{{ route('dashboard') }}" class="group relative bg-white rounded-2xl border border-neutral-200 p-6 sm:p-8 shadow-sm hover:shadow-xl hover:border-accent-300 transition-all duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="bar-chart-3" class="w-6 h-6"></i>
                </div>
                <h3 class="text-lg font-semibold text-neutral-900 mb-2">Dashboard BOK</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">
                    Akses sistem keuangan BOK — LPJ, RAB, POA, anggaran, dan laporan keuangan.
                </p>
                <div class="mt-4 flex items-center gap-1 text-accent-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Buka</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </a>

            {{-- Card 3: Admin CMS (admin only) --}}
            @if(auth()->user()->canManageContent())
            <a href="{{ route('pkm-admin.dashboard') }}" class="group relative bg-white rounded-2xl border border-neutral-200 p-6 sm:p-8 shadow-sm hover:shadow-xl hover:border-violet-300 transition-all duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="settings" class="w-6 h-6"></i>
                </div>
                <h3 class="text-lg font-semibold text-neutral-900 mb-2">Admin CMS PKM</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">
                    Kelola konten website — berita, galeri, kategori, dan pesan masuk dari pengunjung.
                </p>
                <div class="mt-4 flex items-center gap-1 text-violet-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Buka</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </a>
            @endif
        </div>

        {{-- Logout link --}}
        <div class="text-center mt-8">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm text-neutral-400 hover:text-neutral-600 transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4 inline-block mr-1"></i>
                    Keluar
                </button>
            </form>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
