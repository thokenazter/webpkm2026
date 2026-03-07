/**
 * 3D Interactive Login Character
 * Handles character animations and interactions
 */

class LoginCharacter {
    constructor() {
        this.character = document.querySelector('.character');
        this.eyes = document.querySelectorAll('.eye');
        this.mouth = document.querySelector('.character-mouth');
        this.emailInput = document.getElementById('email');
        this.passwordInput = document.getElementById('password');
        this.loginForm = document.getElementById('loginForm') || document.getElementById('registerForm');
        this.loginButton = document.querySelector('.login-button');
        
        this.isBlinking = false;
        this.blinkInterval = null;
        this.mousePosition = { x: 0, y: 0 };
        this.typingTimeout = null;
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.startBlinking();
        this.setupMouseTracking();
        this.handleExistingErrors();
        this.startIdleAnimation();
    }
    
    setupEventListeners() {
        // Email field focus - typing animation
        if (this.emailInput) {
            this.emailInput.addEventListener('focus', () => {
                this.setExpression('typing');
            });
            
            this.emailInput.addEventListener('blur', () => {
                this.setExpression('idle');
            });
            
            // Typing animation while user types
            this.emailInput.addEventListener('input', () => {
                this.setExpression('typing');
                clearTimeout(this.typingTimeout);
                this.typingTimeout = setTimeout(() => {
                    if (document.activeElement !== this.emailInput) {
                        this.setExpression('idle');
                    }
                }, 1000);
            });
        }
        
        // Password field focus - blink then cover eyes
        if (this.passwordInput) {
            this.passwordInput.addEventListener('focus', () => {
                this.blinkThenCoverEyes();
            });
            
            this.passwordInput.addEventListener('blur', () => {
                this.uncoverEyesThenIdle();
            });
            
            // Keep eyes covered while typing password
            this.passwordInput.addEventListener('input', () => {
                // Ensure eyes stay covered during password input
                if (!this.character.classList.contains('covering-eyes')) {
                    this.setExpression('covering-eyes');
                }
                // No typing animation for password - keep eyes covered
            });
        }
        
        // Name field for register page
        const nameInput = document.getElementById('name');
        if (nameInput) {
            nameInput.addEventListener('focus', () => {
                this.setExpression('happy');
            });
            
            nameInput.addEventListener('blur', () => {
                this.setExpression('idle');
            });
            
            nameInput.addEventListener('input', () => {
                this.setExpression('typing');
                clearTimeout(this.typingTimeout);
                this.typingTimeout = setTimeout(() => {
                    if (document.activeElement !== nameInput) {
                        this.setExpression('happy');
                    }
                }, 1000);
            });
        }
        
        // Confirm password field for register page
        const confirmPasswordInput = document.getElementById('password_confirmation');
        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('focus', () => {
                this.blinkThenCoverEyes();
            });
            
            confirmPasswordInput.addEventListener('blur', () => {
                this.uncoverEyesThenIdle();
            });
            
            // Keep eyes covered while typing confirm password
            confirmPasswordInput.addEventListener('input', () => {
                // Ensure eyes stay covered during password confirmation input
                if (!this.character.classList.contains('covering-eyes')) {
                    this.setExpression('covering-eyes');
                }
                // No typing animation for password confirmation - keep eyes covered
            });
        }
        
        // Form submission
        if (this.loginForm) {
            this.loginForm.addEventListener('submit', (e) => {
                this.handleFormSubmit(e);
            });
        }
        
        // Character click easter egg
        if (this.character) {
            this.character.addEventListener('dblclick', () => {
                this.spinAnimation();
            });
        }
        
        // Konami code easter egg
        this.setupKonamiCode();
    }
    
    setupMouseTracking() {
        document.addEventListener('mousemove', (e) => {
            this.mousePosition.x = e.clientX;
            this.mousePosition.y = e.clientY;
            this.updateEyePosition();
        });
    }
    
    updateEyePosition() {
        if (this.character.classList.contains('covering-eyes') || this.isBlinking) {
            return;
        }
        
        const characterRect = this.character.getBoundingClientRect();
        const characterCenterX = characterRect.left + characterRect.width / 2;
        const characterCenterY = characterRect.top + characterRect.height / 2;
        
        const deltaX = this.mousePosition.x - characterCenterX;
        const deltaY = this.mousePosition.y - characterCenterY;
        
        // Limit eye movement
        const maxMovement = 3;
        const moveX = Math.max(-maxMovement, Math.min(maxMovement, deltaX / 20));
        const moveY = Math.max(-maxMovement, Math.min(maxMovement, deltaY / 20));
        
        this.eyes.forEach(eye => {
            eye.style.transform = `translate(${moveX}px, ${moveY}px)`;
        });
    }
    
    startBlinking() {
        this.blinkInterval = setInterval(() => {
            if (!this.character.classList.contains('covering-eyes')) {
                this.blink();
            }
        }, 3000);
    }
    
    blink() {
        this.isBlinking = true;
        this.character.classList.add('blinking');
        
        setTimeout(() => {
            this.character.classList.remove('blinking');
            this.isBlinking = false;
        }, 300);
    }
    
    setExpression(expression) {
        // Remove all expression classes
        this.character.classList.remove('idle', 'typing', 'error-state', 'loading-state', 'success-state', 'happy', 'covering-eyes', 'sad', 'error', 'celebrating', 'mobile-optimized');
        
        // Add new expression
        if (expression !== 'normal') {
            this.character.classList.add(expression);
        }
    }
    
    startIdleAnimation() {
        // Start with idle animation
        this.setExpression('idle');
    }
    
    blinkThenCoverEyes() {
        // First do a more deliberate password blink
        this.isBlinking = true;
        this.character.classList.add('password-blink');
        
        // Detect mobile for different timing
        const isMobile = window.innerWidth <= 480;
        const blinkDuration = isMobile ? 350 : 400;
        
        // After enhanced blink animation, cover eyes
        setTimeout(() => {
            this.character.classList.remove('password-blink');
            this.isBlinking = false;
            this.setExpression('covering-eyes');
            
            // Add smooth transition class for better mobile experience
            if (isMobile) {
                this.character.classList.add('mobile-optimized');
            }
        }, blinkDuration);
    }
    
    uncoverEyesThenIdle() {
        // Remove mobile optimization class
        this.character.classList.remove('mobile-optimized');
        
        // Smooth transition from covering eyes to idle
        this.setExpression('idle');
        
        // Detect mobile for different timing
        const isMobile = window.innerWidth <= 480;
        const uncoverDelay = isMobile ? 150 : 200;
        
        // Add a small blink after uncovering
        setTimeout(() => {
            if (!this.character.classList.contains('covering-eyes')) {
                this.blink();
            }
        }, uncoverDelay);
    }
    
    showError() {
        // Use new error-state animation
        this.setExpression('error-state');
        
        // Add error class to inputs with errors
        const errorInputs = document.querySelectorAll('.form-input');
        errorInputs.forEach(input => {
            const errorSpan = input.parentNode.querySelector('.error-text');
            if (errorSpan) {
                input.classList.add('error');
            }
        });
        
        // Return to idle after error animation
        setTimeout(() => {
            this.setExpression('idle');
            errorInputs.forEach(input => {
                input.classList.remove('error');
            });
        }, 2000);
    }
    
    showSuccess() {
        // Start celebration sequence with new success animation
        this.startCelebration();
    }
    
    startCelebration() {
        // Set new success-state expression
        this.setExpression('success-state');
        
        // Create enhanced success message
        this.createEnhancedSuccessMessage();
        
        // Add confetti
        this.createConfetti();
        
        // Add extra celebration effects
        this.addCelebrationEffects();
        
        // Extended celebration sequence - 5 seconds for better experience
        setTimeout(() => {
            this.fadeOutAndRedirect();
        }, 5000); // 5 seconds celebration before redirect
    }
    
    createEnhancedSuccessMessage() {
        const successDiv = document.createElement('div');
        successDiv.className = 'enhanced-success-celebration';
        successDiv.innerHTML = `
            <div class="success-icon">🎉</div>
            <h2>Login Berhasil!</h2>
            <p>Selamat datang kembali!</p>
            <div class="success-loading">
                <div class="loading-dots">
                    <span></span><span></span><span></span>
                </div>
                <p>Mengarahkan ke dashboard...</p>
            </div>
        `;
        document.body.appendChild(successDiv);
        
        // Remove after animation
        setTimeout(() => {
            if (successDiv.parentNode) {
                successDiv.parentNode.removeChild(successDiv);
            }
        }, 5500);
    }
    
    addCelebrationEffects() {
        // Add screen flash effect
        const flashDiv = document.createElement('div');
        flashDiv.style.position = 'fixed';
        flashDiv.style.top = '0';
        flashDiv.style.left = '0';
        flashDiv.style.width = '100%';
        flashDiv.style.height = '100%';
        flashDiv.style.background = 'rgba(255, 255, 255, 0.3)';
        flashDiv.style.zIndex = '998';
        flashDiv.style.animation = 'success-flash 0.5s ease-out';
        flashDiv.style.pointerEvents = 'none';
        
        document.body.appendChild(flashDiv);
        
        setTimeout(() => {
            if (flashDiv.parentNode) {
                flashDiv.parentNode.removeChild(flashDiv);
            }
        }, 500);
    }
    
    fadeOutAndRedirect() {
        // Fade out the entire page
        document.body.style.transition = 'opacity 0.8s ease-out';
        document.body.style.opacity = '0';
        
        // Redirect after fade
        setTimeout(() => {
            this.redirectToDashboard();
        }, 800);
    }
    
    createSuccessMessage() {
        const successDiv = document.createElement('div');
        successDiv.className = 'success-celebration';
        successDiv.innerHTML = `
            <h2>🎉 Success!</h2>
            <p>Welcome! Redirecting...</p>
        `;
        document.body.appendChild(successDiv);
        
        // Remove after animation
        setTimeout(() => {
            if (successDiv.parentNode) {
                successDiv.parentNode.removeChild(successDiv);
            }
        }, 3000);
    }
    
    createConfetti() {
        const confettiContainer = document.createElement('div');
        confettiContainer.style.position = 'fixed';
        confettiContainer.style.top = '0';
        confettiContainer.style.left = '0';
        confettiContainer.style.width = '100%';
        confettiContainer.style.height = '100%';
        confettiContainer.style.pointerEvents = 'none';
        confettiContainer.style.zIndex = '999';
        
        // Create multiple confetti pieces
        for (let i = 0; i < 50; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.position = 'absolute';
            confetti.style.width = '10px';
            confetti.style.height = '10px';
            confetti.style.left = Math.random() * 100 + '%';
            confetti.style.animationDelay = Math.random() * 2 + 's';
            confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
            confetti.style.animation = 'confetti-fall 3s linear infinite';
            
            // Random colors
            const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#ffeaa7', '#dda0dd', '#98d8c8', '#f7dc6f', '#bb8fce'];
            confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
            
            confettiContainer.appendChild(confetti);
        }
        
        // Add confetti keyframes
        if (!document.querySelector('#confetti-keyframes')) {
            const style = document.createElement('style');
            style.id = 'confetti-keyframes';
            style.textContent = `
                @keyframes confetti-fall {
                    0% {
                        transform: translateY(-100vh) rotate(0deg);
                        opacity: 1;
                    }
                    100% {
                        transform: translateY(100vh) rotate(720deg);
                        opacity: 0;
                    }
                }
                .success-celebration {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: linear-gradient(135deg, #4ecdc4, #44a08d);
                    color: white;
                    padding: 30px 40px;
                    border-radius: 20px;
                    text-align: center;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
                    z-index: 1000;
                    animation: successPop 0.6s ease-out;
                }
                .success-celebration h2 {
                    margin: 0 0 10px 0;
                    font-size: 24px;
                    font-weight: 700;
                }
                .success-celebration p {
                    margin: 0;
                    font-size: 16px;
                    opacity: 0.9;
                }
                @keyframes successPop {
                    0% {
                        opacity: 0;
                        transform: translate(-50%, -50%) scale(0.5);
                    }
                    50% {
                        transform: translate(-50%, -50%) scale(1.1);
                    }
                    100% {
                        opacity: 1;
                        transform: translate(-50%, -50%) scale(1);
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        document.body.appendChild(confettiContainer);
        
        // Remove confetti after animation
        setTimeout(() => {
            if (confettiContainer.parentNode) {
                confettiContainer.parentNode.removeChild(confettiContainer);
            }
        }, 4000);
    }
    
    redirectToDashboard() {
        // Use stored redirect URL or fallback to dashboard
        const redirectUrl = this.redirectUrl || document.querySelector('meta[name="redirect-url"]')?.content || '/dashboard';
        window.location.href = redirectUrl;
    }
    
    handleFormSubmit(e) {
        // Show loading state with new loading animation
        this.loginButton.classList.add('loading');
        this.loginButton.textContent = '';
        
        // Set loading expression during form submission
        this.setExpression('loading-state');
        
        // If there are client-side validation errors, prevent submission
        const requiredFields = this.loginForm.querySelectorAll('[required]');
        let hasErrors = false;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                hasErrors = true;
                field.classList.add('error');
            }
        });
        
        if (hasErrors) {
            e.preventDefault();
            this.showError();
            this.loginButton.classList.remove('loading');
            this.loginButton.textContent = this.loginForm.id === 'registerForm' ? 'Create Account' : 'Log in';
            return;
        }
        
        // For register form, allow normal form submission for proper redirect
        if (this.loginForm.id === 'registerForm') {
            // Don't prevent default - let Laravel handle the redirect
            return;
        }
        
        // For login form, intercept form submission for success animation
        e.preventDefault();
        this.submitFormWithCelebration();
    }
    
    submitFormWithCelebration() {
        const formData = new FormData(this.loginForm);
        
        fetch(this.loginForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                // Check if it's a redirect (successful login)
                if (response.redirected || response.url.includes('dashboard') || response.url.includes('home')) {
                    // Success! Show celebration before redirect
                    this.showSuccessWithRedirect(response.url);
                } else {
                    // Check response content for success indicators
                    return response.text().then(html => {
                        if (html.includes('dashboard') || html.includes('home')) {
                            // Success detected
                            this.showSuccessWithRedirect('/dashboard');
                        } else if (html.includes('error') || html.includes('invalid') || html.includes('incorrect') || html.includes('failed')) {
                            // Error detected - show error animation then reload
                            this.showErrorThenReload();
                        } else {
                            // Unclear response, assume success
                            this.showSuccessWithRedirect('/dashboard');
                        }
                    });
                }
            } else if (response.status === 422) {
                // Validation errors (422 Unprocessable Entity)
                this.showErrorThenReload();
            } else if (response.status === 401 || response.status === 403) {
                // Authentication errors
                this.showErrorThenReload();
            } else {
                // Other errors, reload page to show Laravel errors
                window.location.reload();
            }
        })
        .catch(error => {
            // Network error or other issues, fallback to normal form submission
            this.loginForm.submit();
        });
    }
    
    showSuccessWithRedirect(redirectUrl) {
        // Store redirect URL for later use
        this.redirectUrl = redirectUrl;
        
        // Start celebration sequence
        this.showSuccess();
    }
    
    showErrorThenReload() {
        // Reset loading state
        this.loginButton.classList.remove('loading');
        this.loginButton.textContent = this.loginForm.id === 'registerForm' ? 'Create Account' : 'Log in';
        
        // Show error animation
        this.showError();
        
        // Reload page after error animation to show Laravel errors
        setTimeout(() => {
            window.location.reload();
        }, 2500); // Give time for error animation to play
    }
    
    handleExistingErrors() {
        // Check if there are Laravel validation errors on page load
        const errorElements = document.querySelectorAll('.error-text');
        if (errorElements.length > 0) {
            setTimeout(() => {
                this.showError();
            }, 500);
        }
        
        // Check for success messages
        const successElements = document.querySelectorAll('.success-message');
        if (successElements.length > 0) {
            setTimeout(() => {
                this.showSuccess();
            }, 500);
        }
    }
    
    spinAnimation() {
        this.character.style.animation = 'spin 1s ease-in-out';
        setTimeout(() => {
            this.character.style.animation = '';
            this.setExpression('idle');
        }, 1000);
    }
    
    setupKonamiCode() {
        const konamiCode = [
            'ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown',
            'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight',
            'KeyB', 'KeyA'
        ];
        let konamiIndex = 0;
        
        document.addEventListener('keydown', (e) => {
            if (e.code === konamiCode[konamiIndex]) {
                konamiIndex++;
                if (konamiIndex === konamiCode.length) {
                    this.konamiEasterEgg();
                    konamiIndex = 0;
                }
            } else {
                konamiIndex = 0;
            }
        });
    }
    
    konamiEasterEgg() {
        // Create rainbow effect
        this.character.style.filter = 'hue-rotate(0deg)';
        this.character.style.animation = 'rainbow 2s linear infinite';
        
        // Add rainbow keyframes if not exists
        if (!document.querySelector('#rainbow-keyframes')) {
            const style = document.createElement('style');
            style.id = 'rainbow-keyframes';
            style.textContent = `
                @keyframes rainbow {
                    0% { filter: hue-rotate(0deg); }
                    100% { filter: hue-rotate(360deg); }
                }
            `;
            document.head.appendChild(style);
        }
        
        setTimeout(() => {
            this.character.style.filter = '';
            this.character.style.animation = '';
            this.setExpression('idle');
        }, 2000);
    }
    
    destroy() {
        if (this.blinkInterval) {
            clearInterval(this.blinkInterval);
        }
        if (this.typingTimeout) {
            clearTimeout(this.typingTimeout);
        }
    }
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize main character
    const character = new LoginCharacter();
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        character.destroy();
    });
});

// Export for potential external use
window.LoginCharacter = LoginCharacter;