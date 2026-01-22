@extends('layouts.app')

@section('title', 'Login Admin - Puskesmas')

@section('content')
    <div
        class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-primary-50 to-white">
        <div class="max-w-md w-full">
            {{-- Logo --}}
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Puskesmas" class="w-20 h-20 mx-auto mb-4">
                <h1 class="text-2xl font-bold text-neutral-900">Admin Panel</h1>
                <p class="text-neutral-600 mt-1">Puskesmas Rawat Inap Kabalsiang Benjuring</p>
            </div>

            {{-- Login Form --}}
            <div class="card p-8">
                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-neutral-700 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="mail" class="w-5 h-5 text-neutral-400"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full pl-10 pr-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('email') border-red-500 @enderror"
                                placeholder="admin@puskesmas.go.id" required autofocus>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-neutral-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-neutral-400"></i>
                            </div>
                            <input type="password" id="password" name="password"
                                class="w-full pl-10 pr-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('password') border-red-500 @enderror"
                                placeholder="••••••••" required>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="w-4 h-4 text-primary-600 border-neutral-300 rounded focus:ring-primary-500">
                        <label for="remember" class="ml-2 text-sm text-neutral-600">Ingat saya</label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="w-full btn btn-primary py-3 text-base">
                        <i data-lucide="log-in" class="w-5 h-5"></i>
                        Masuk
                    </button>
                </form>
            </div>

            {{-- Back to Home --}}
            <div class="text-center mt-6">
                <a href="/" class="text-sm text-neutral-600 hover:text-primary-600 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4 inline mr-1"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
@endsection