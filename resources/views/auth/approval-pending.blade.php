<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LPJ BOK Puskesmas') }} - Menunggu Persetujuan</title>

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

    <!-- Floating shapes for visual appeal -->
    <div class="fixed inset-0 -z-10">
        <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full animate-float"></div>
        <div class="absolute top-20 right-20 w-32 h-32 bg-white/5 rounded-full animate-float delay-200"></div>
        <div class="absolute bottom-20 left-20 w-16 h-16 bg-white/10 rounded-full animate-float delay-400"></div>
        <div class="absolute bottom-10 right-10 w-24 h-24 bg-white/5 rounded-full animate-float delay-600"></div>
    </div>

    <!-- Main Content -->
    <section class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Glass Card -->
            <div class="glass rounded-3xl p-8 shadow-2xl animate-fade-in-up">
                <!-- Logo Section -->
                <div class="text-center mb-8 animate-fade-in-down delay-200">
                    <div class="mx-auto w-20 h-20 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mb-4 animate-pulse-slow">
                        <i class="fas fa-clock text-white text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-white mb-2">
                        Sistem
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500">
                            LPJ BOK
                        </span>
                    </h1>
                    <p class="text-white/80 text-sm">
                        Puskesmas Rawat Inap Kabalsiang Benjuring
                    </p>
                </div>

                <!-- Message Alert -->
                @if (session('message'))
                    <div class="mb-6 p-4 bg-gradient-to-r from-yellow-400 to-orange-500/20 border border-yellow-400/30 text-white rounded-xl animate-fade-in-up delay-300">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle mr-3 text-yellow-400"></i>
                            <span>{{ session('message') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Main Content -->
                <div class="text-center animate-fade-in-up delay-400">
                    <!-- Animated Icon -->
                    <div class="flex justify-center mb-6">
                        <div class="relative">
                            <div class="w-24 h-24 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center animate-pulse-slow">
                                <i class="fas fa-user-clock text-white text-3xl"></i>
                            </div>
                            <!-- Animated rings -->
                            <div class="absolute inset-0 border-4 border-yellow-400/30 rounded-full animate-ping"></div>
                            <div class="absolute inset-0 border-4 border-orange-500/20 rounded-full animate-ping delay-200"></div>
                        </div>
                    </div>

                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-white mb-4 animate-fade-in-up delay-500">
                        Akun Menunggu Persetujuan
                    </h2>

                    <!-- Description -->
                    <div class="text-white/90 mb-8 space-y-3 animate-fade-in-up delay-600">
                        <p class="leading-relaxed">
                            <i class="fas fa-check-circle text-green-400 mr-2"></i>
                            Akun Anda telah berhasil dibuat!
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-4 animate-fade-in-up delay-700">
                        <a href="{{ route('login') }}" 
                           class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center justify-center btn-modern">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Login
                        </a>
                        
                        <a href="{{ url('/') }}" 
                           class="w-full bg-white/10 text-white font-semibold py-3 px-6 rounded-xl hover:bg-white/20 transition-all duration-300 transform hover:scale-105 border border-white/20 flex items-center justify-center btn-modern">
                            <i class="fas fa-home mr-2"></i>
                            Kembali ke Beranda
                        </a>
                    </div>

                    <!-- Contact Info -->
                    {{-- <div class="mt-8 pt-6 border-t border-white/20 animate-fade-in-up delay-800">
                        <p class="text-white/70 text-sm mb-2">
                            <i class="fas fa-phone mr-2"></i>
                            Butuh bantuan? Hubungi Administrator
                        </p>
                        <div class="flex justify-center space-x-4 text-sm">
                            <span class="text-white/60">
                                <i class="fas fa-envelope mr-1"></i>
                                admin@puskesmas.test
                            </span>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>

    <!-- Auto refresh script -->
    <script>
        // Auto refresh every 30 seconds to check if approved
        setTimeout(function() {
            window.location.reload();
        }, 30000);

        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add click effect to buttons
            const buttons = document.querySelectorAll('.btn-modern');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });
    </script>

    <style>
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</body>
</html>