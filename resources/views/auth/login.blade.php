<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Puskesmas Kabalsiang Benjuring</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #065f46 0%, #0d9488 50%, #14b8a6 100%);
            position: relative;
            overflow: hidden;
        }

        /* Floating decorative shapes */
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.08;
            background: white;
        }
        .bg-shape:nth-child(1) { width: 400px; height: 400px; top: -100px; left: -100px; animation: float1 15s ease-in-out infinite; }
        .bg-shape:nth-child(2) { width: 300px; height: 300px; bottom: -80px; right: -80px; animation: float2 18s ease-in-out infinite; }
        .bg-shape:nth-child(3) { width: 200px; height: 200px; top: 50%; left: 60%; animation: float3 12s ease-in-out infinite; }
        .bg-shape:nth-child(4) { width: 150px; height: 150px; top: 20%; right: 20%; animation: float1 20s ease-in-out infinite reverse; }

        @keyframes float1 { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(30px, -30px); } }
        @keyframes float2 { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(-20px, 20px); } }
        @keyframes float3 { 0%, 100% { transform: translate(0, 0) scale(1); } 50% { transform: translate(15px, -15px) scale(1.05); } }

        /* Login card */
        .login-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            margin: 1rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 1.5rem;
            box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.25);
            overflow: visible;
            padding: 2.5rem 2rem 2rem;
        }

        /* Mascot area */
        .mascot-wrapper {
            display: flex;
            justify-content: center;
            margin-top: -80px;
            margin-bottom: 0.5rem;
        }
        .mascot-wrapper img {
            width: 120px;
            height: 120px;
            object-fit: contain;
            filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.15));
        }

        /* Branding */
        .brand-section {
            text-align: center;
            margin-bottom: 1.75rem;
        }
        .brand-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #065f46;
            margin: 0;
        }
        .brand-subtitle {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        /* Form styles */
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.4rem;
        }
        .form-input {
            width: 100%;
            padding: 0.7rem 0.9rem;
            border: 1.5px solid #d1d5db;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            color: #1f2937;
            background: #f9fafb;
            transition: all 0.2s ease;
            box-sizing: border-box;
            outline: none;
        }
        .form-input:focus {
            border-color: #0d9488;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
        }
        .form-input.error { border-color: #ef4444; }
        .form-input::placeholder { color: #9ca3af; }

        /* Options row */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.8rem;
        }
        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #4b5563;
            cursor: pointer;
        }
        .remember-label input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            accent-color: #0d9488;
        }
        .forgot-link {
            color: #0d9488;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .forgot-link:hover { color: #065f46; }

        /* Submit button */
        .login-btn {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(135deg, #0d9488, #065f46);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
        }
        .login-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(13, 148, 136, 0.4);
        }
        .login-btn:active { transform: translateY(0); }

        /* Register link */
        .register-link {
            text-align: center;
            margin-top: 1.25rem;
            font-size: 0.8rem;
            color: #6b7280;
        }
        .register-link a {
            color: #0d9488;
            text-decoration: none;
            font-weight: 600;
        }
        .register-link a:hover { color: #065f46; text-decoration: underline; }

        /* Alert messages */
        .alert-success {
            padding: 0.65rem 0.9rem;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 0.75rem;
            color: #065f46;
            font-size: 0.8rem;
            margin-bottom: 1.25rem;
        }
        .alert-error {
            padding: 0.65rem 0.9rem;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 0.75rem;
            color: #991b1b;
            font-size: 0.8rem;
            margin-bottom: 1.25rem;
        }

        /* Back to home */
        .back-home {
            position: fixed;
            top: 1.5rem;
            left: 1.5rem;
            z-index: 20;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(8px);
            transition: all 0.2s;
        }
        .back-home:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Floating Background Shapes -->
    <div class="bg-shape"></div>
    <div class="bg-shape"></div>
    <div class="bg-shape"></div>
    <div class="bg-shape"></div>

    <!-- Back to Home -->
    <a href="{{ url('/') }}" class="back-home">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Beranda
    </a>

    <!-- Login Card -->
    <div class="login-card">
        <!-- Animated Mascot -->
        <div class="mascot-wrapper">
            <img src="{{ asset('animasi.webp') }}" alt="Maskot Puskesmas">
        </div>

        <!-- Branding -->
        <div class="brand-section">
            <h1 class="brand-title">Puskesmas Kabalsiang Benjuring</h1>
            <p class="brand-subtitle">Masuk ke akun Anda</p>
        </div>

        <!-- Success Message -->
        @session('status')
            <div class="alert-success" role="alert">{{ $value }}</div>
        @endsession

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert-error" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="form-input @error('email') error @enderror"
                    placeholder="contoh@email.com"
                    required autofocus autocomplete="username">
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password"
                    class="form-input @error('password') error @enderror"
                    placeholder="••••••••"
                    required autocomplete="current-password">
            </div>

            <!-- Options -->
            <div class="form-options">
                <label class="remember-label">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Ingat Saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>
                @endif
            </div>

            <!-- Submit -->
            <button type="submit" class="login-btn">Masuk</button>

            <!-- Register -->
            {{-- Register (disabled for now)
            @if (Route::has('register'))
                <div class="register-link">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar Disini</a>
                </div>
            @endif
            --}}
        </form>
    </div>

    @livewireScripts
</body>
</html>