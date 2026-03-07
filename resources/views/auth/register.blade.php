<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Register</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- 3D Login Styles -->
    <link href="{{ asset('css/3d-login.css') }}" rel="stylesheet">
    <link href="{{ asset('css/3d-login-dark.css') }}" rel="stylesheet">
    <link href="{{ asset('css/crab-extras.css') }}" rel="stylesheet">
    <link href="{{ asset('css/page-transitions.css') }}" rel="stylesheet">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="login-container">
        <!-- Floating Background Shapes -->
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        
        <!-- Main Register Card -->
        <div class="login-card">
            <!-- 3D Character -->
            <div class="character-container">
                <div class="character" role="img" aria-label="Register mascot character">
                    <div class="character-head">
                        <div class="character-eyes">
                            <div class="eye"></div>
                            <div class="eye"></div>
                        </div>
                    </div>
                    <div class="character-mouth"></div>
                    <div class="character-hands">
                        <div class="hand"></div>
                        <div class="hand"></div>
                    </div>
                </div>
            </div>
            
            <!-- Register Title -->
            <h1 class="login-title">Buat Akun</h1>
            
            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="error-container">
                    @foreach ($errors->all() as $error)
                        <div class="error-text">{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                
                <!-- Name Field -->
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Nama') }}</label>
                    <input id="name" 
                           class="form-input" 
                           type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus 
                           autocomplete="name"
                           placeholder="Masukan Nama Lengkap">
                    @error('name')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input id="email" 
                           class="form-input" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autocomplete="username"
                           placeholder="Masukan Alamat Email">
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input id="password" 
                           class="form-input" 
                           type="password" 
                           name="password" 
                           required 
                           autocomplete="new-password"
                           placeholder="">
                    @error('password')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">{{ __('Konfirmasi Password') }}</label>
                    <input id="password_confirmation" 
                           class="form-input" 
                           type="password" 
                           name="password_confirmation" 
                           required 
                           autocomplete="new-password"
                           placeholder="Ulangi Password">
                    @error('password_confirmation')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Terms and Privacy Policy -->
                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="form-group">
                        <div class="terms-checkbox">
                            <input type="checkbox" 
                                   id="terms" 
                                   name="terms" 
                                   required 
                                   class="checkbox-input">
                            <label for="terms" class="checkbox-label">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="terms-link">'.__('Terms of Service').'</a>',
                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="terms-link">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </label>
                        </div>
                        @error('terms')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
                
                <!-- Submit Button -->
                <button type="submit" class="login-button">
                    {{ __('Buat Akun') }}
                </button>
                
                <!-- Login Link -->
                <div class="form-options" style="justify-content: center; margin-top: 20px;">
                    <a href="{{ route('login') }}" data-page-transition class="forgot-password">
                        {{ __('Sudah Punya Akun? Masuk Disini') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Success Message Template -->
    @if (session('status'))
        <div class="success-message">
            {{ session('status') }}
        </div>
    @endif
    
    <!-- 3D Character Script -->
    <script src="{{ asset('js/3d-login.js') }}"></script>
    <script src="{{ asset('js/page-transitions-simple.js') }}"></script>
    
    <!-- Register-specific enhancements -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize character for register page
            const character = new LoginCharacter();
            
            // Override some behaviors for register page
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            
            // Name field - happy expression
            if (nameInput) {
                nameInput.addEventListener('focus', () => {
                    character.setExpression('happy');
                });
                
                nameInput.addEventListener('blur', () => {
                    character.setExpression('idle');
                });
                
                nameInput.addEventListener('input', () => {
                    character.setExpression('typing');
                    clearTimeout(character.typingTimeout);
                    character.typingTimeout = setTimeout(() => {
                        if (document.activeElement !== nameInput) {
                            character.setExpression('happy');
                        }
                    }, 1000);
                });
            }
            
            // Confirm password - also cover eyes
            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener('focus', () => {
                    character.blinkThenCoverEyes();
                });
                
                confirmPasswordInput.addEventListener('blur', () => {
                    character.uncoverEyesThenIdle();
                });
                
                confirmPasswordInput.addEventListener('input', () => {
                    character.setExpression('typing');
                    clearTimeout(character.typingTimeout);
                    character.typingTimeout = setTimeout(() => {
                        if (document.activeElement !== confirmPasswordInput) {
                            character.setExpression('covering-eyes');
                        }
                    }, 500);
                });
            }
            
            // Handle Laravel validation errors
            @if ($errors->any())
                setTimeout(() => {
                    character.showError();
                }, 500);
            @endif
            
            // Handle success registration
            @if (session('status'))
                setTimeout(() => {
                    character.showSuccess();
                }, 500);
            @endif
        });
    </script>
</body>
</html>
