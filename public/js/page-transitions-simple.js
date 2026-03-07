/**
 * Simple Page Transition Handler - Only for Navigation Links
 */

class SimplePageTransition {
    constructor() {
        this.isTransitioning = false;
        this.init();
    }

    init() {
        console.log('Simple page transitions initializing...');
        
        // Create overlay
        this.createTransitionOverlay();
        
        // Only bind to specific navigation links with data attribute
        this.bindNavigationLinks();
        
        // Handle page enter animation
        this.handlePageEnter();
    }

    createTransitionOverlay() {
        const overlay = document.createElement('div');
        overlay.className = 'transition-overlay';
        overlay.innerHTML = `
            <div class="transition-scene">
                <div class="crab-journey-container">
                    <!-- Walking Crab -->
                    <div class="walking-crab">
                        <div class="crab-body">
                            <div class="crab-shell"></div>
                            <div class="crab-eye left-eye">
                                <div class="eye-stalk"></div>
                                <div class="eye-ball"></div>
                            </div>
                            <div class="crab-eye right-eye">
                                <div class="eye-stalk"></div>
                                <div class="eye-ball"></div>
                            </div>
                            <div class="crab-claw left-claw"></div>
                            <div class="crab-claw right-claw"></div>
                            <div class="crab-legs">
                                <div class="leg leg-1"></div>
                                <div class="leg leg-2"></div>
                                <div class="leg leg-3"></div>
                                <div class="leg leg-4"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Journey Path -->
                    <div class="journey-path">
                        <div class="path-line"></div>
                        <div class="path-dots">
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <div class="dot"></div>
                        </div>
                    </div>
                    
                    <!-- Destination Icons -->
                    <div class="destination-icons">
                        <div class="destination-icon login-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 7C14.4 7 14 7.4 14 8S14.4 9 15 9H21ZM15 11C14.4 11 14 11.4 14 12S14.4 13 15 13H21V11H15ZM21 15H15C14.4 15 14 15.4 14 16S14.4 17 15 17H21V15ZM9 7H3V9H9C9.6 9 10 8.6 10 8S9.6 7 9 7ZM9 11H3V13H9C9.6 13 10 12.6 10 12S9.6 11 9 11ZM9 15H3V17H9C9.6 17 10 16.6 10 16S9.6 15 9 15Z"/>
                            </svg>
                        </div>
                        <div class="destination-icon register-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M6,10V7H4V10H1V12H4V15H6V12H9V10M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12Z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Loading Text -->
                    <div class="loading-text">
                        <p class="journey-message">Kepiting sedang berjalan...</p>
                        <div class="loading-dots">
                            <span>.</span>
                            <span>.</span>
                            <span>.</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);
        this.overlay = overlay;
    }

    bindNavigationLinks() {
        // Only bind to links with specific data attribute to avoid conflicts
        const navLinks = document.querySelectorAll('[data-page-transition]');
        console.log('Found navigation links with data-page-transition:', navLinks.length);
        
        navLinks.forEach(link => {
            console.log('Binding transition to:', link.href);
            
            link.addEventListener('click', (e) => {
                if (this.isTransitioning) {
                    e.preventDefault();
                    return;
                }
                
                const targetUrl = link.href;
                const currentPath = window.location.pathname;
                
                console.log('Navigation transition:', currentPath, '->', targetUrl);
                
                e.preventDefault();
                this.startTransition(targetUrl, currentPath);
            });
        });
    }

    startTransition(targetUrl, fromPath) {
        if (this.isTransitioning) return;
        
        this.isTransitioning = true;
        console.log('Starting transition to:', targetUrl);
        
        // Determine journey message
        let journeyMessage = 'Kepiting sedang berjalan...';
        let targetIcon = 'login-icon';
        
        if (targetUrl.includes('/register')) {
            journeyMessage = 'Menuju halaman pendaftaran...';
            targetIcon = 'register-icon';
        } else if (targetUrl.includes('/login')) {
            journeyMessage = 'Menuju halaman masuk...';
            targetIcon = 'login-icon';
        }

        // Start exit animations
        const loginCard = document.querySelector('.login-card');
        const character = document.querySelector('.character');
        
        if (loginCard) {
            loginCard.style.transition = 'all 0.6s ease-out';
            loginCard.style.opacity = '0';
            loginCard.style.transform = 'translateY(-50px) scale(0.95)';
        }

        if (character) {
            character.style.transition = 'all 0.6s ease-out';
            character.style.transform = 'scale(0.8) rotate(-10deg)';
            character.style.opacity = '0.7';
        }

        // Show overlay
        setTimeout(() => {
            this.overlay.classList.add('active');
            
            const messageEl = this.overlay.querySelector('.journey-message');
            const targetIconEl = this.overlay.querySelector('.' + targetIcon);
            const walkingCrab = this.overlay.querySelector('.walking-crab');
            
            if (messageEl) {
                messageEl.textContent = journeyMessage;
            }
            
            if (targetIconEl) {
                targetIconEl.style.background = 'rgba(255,255,255,0.4)';
                targetIconEl.style.transform = 'scale(1.2)';
                targetIconEl.style.boxShadow = '0 0 20px rgba(255,255,255,0.5)';
            }
            
            // Progressive messages
            setTimeout(() => {
                if (walkingCrab) walkingCrab.classList.add('excited');
                if (messageEl) messageEl.textContent = 'Hampir sampai!';
            }, 1500);
            
            setTimeout(() => {
                if (messageEl) messageEl.textContent = 'Yeay! Sampai tujuan!';
            }, 2500);
        }, 300);

        // Navigate
        setTimeout(() => {
            window.location.href = targetUrl;
        }, 3200);
    }

    handlePageEnter() {
        // Simple enter animation
        const loginCard = document.querySelector('.login-card');
        const character = document.querySelector('.character');
        
        if (loginCard) {
            loginCard.style.opacity = '0';
            loginCard.style.transform = 'translateY(50px) scale(0.95)';
            
            setTimeout(() => {
                loginCard.style.transition = 'all 0.6s ease-out';
                loginCard.style.opacity = '1';
                loginCard.style.transform = 'translateY(0) scale(1)';
            }, 100);
        }

        if (character) {
            character.style.opacity = '0';
            character.style.transform = 'scale(0.8)';
            
            setTimeout(() => {
                character.style.transition = 'all 0.6s ease-out';
                character.style.opacity = '1';
                character.style.transform = 'scale(1)';
            }, 300);
        }
    }
}

// Initialize only if we're on login or register page
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    
    if (currentPath.includes('/login') || currentPath.includes('/register')) {
        console.log('Initializing page transitions for auth pages');
        
        try {
            window.simplePageTransition = new SimplePageTransition();
        } catch (error) {
            console.error('Error initializing simple page transitions:', error);
        }
    }
});