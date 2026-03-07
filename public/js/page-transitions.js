/**
 * Page Transition Handler for Login/Register Pages
 */

class PageTransition {
    constructor() {
        this.isTransitioning = false;
        this.init();
    }

    init() {
        // Add transition overlay to body
        this.createTransitionOverlay();
        
        // Handle page enter animation
        this.handlePageEnter();
        
        // Bind transition events to navigation links
        this.bindTransitionEvents();
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

    handlePageEnter() {
        // Determine if coming from login or register
        const currentPath = window.location.pathname;
        const referrer = document.referrer;
        
        let enterClass = 'page-enter';
        
        if (referrer.includes('/login') && currentPath.includes('/register')) {
            enterClass = 'page-enter-from-up';
        } else if (referrer.includes('/register') && currentPath.includes('/login')) {
            enterClass = 'page-enter-from-down';
        }

        // Apply enter animation to login card
        const loginCard = document.querySelector('.login-card');
        if (loginCard) {
            loginCard.style.opacity = '0';
            loginCard.style.transform = 'translateY(50px) scale(0.95)';
            
            setTimeout(() => {
                loginCard.classList.add(enterClass);
                loginCard.style.opacity = '';
                loginCard.style.transform = '';
            }, 100);
        }

        // Animate character entrance
        const character = document.querySelector('.character');
        if (character) {
            character.style.opacity = '0';
            character.style.transform = 'scale(0.8)';
            
            setTimeout(() => {
                character.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                character.style.opacity = '1';
                character.style.transform = 'scale(1)';
                
                // Add wave animation
                setTimeout(() => {
                    character.classList.add('transitioning');
                    setTimeout(() => {
                        character.classList.remove('transitioning');
                    }, 600);
                }, 200);
            }, 300);
        }
    }

    bindTransitionEvents() {
        // Find only specific navigation links for switching between login/register
        // Use more specific selectors to avoid interfering with form actions
        const loginToRegisterLinks = document.querySelectorAll('a[href*="/register"]');
        const registerToLoginLinks = document.querySelectorAll('a[href*="/login"]');
        const navLinks = [...loginToRegisterLinks, ...registerToLoginLinks];
        
        console.log('Found potential navigation links:', navLinks.length);
        
        navLinks.forEach(link => {
            // Skip if this is inside a form or is a form button
            if (link.closest('form') || link.type === 'submit') {
                console.log('Skipping form-related link:', link.href);
                return;
            }
            
            console.log('Adding transition listener to:', link.href, link.textContent.trim());
            
            link.addEventListener('click', (e) => {
                console.log('Link clicked:', link.href);
                
                if (this.isTransitioning) {
                    console.log('Already transitioning, preventing click');
                    e.preventDefault();
                    return;
                }

                const href = link.getAttribute('href');
                const currentPath = window.location.pathname;
                
                console.log('Current path:', currentPath, 'Target:', href);
                
                // Only apply transition if navigating between login/register pages
                if ((currentPath.includes('/login') && href.includes('/register')) ||
                    (currentPath.includes('/register') && href.includes('/login'))) {
                    
                    console.log('Applying page transition');
                    e.preventDefault();
                    this.transitionToPage(href, currentPath, href);
                } else {
                    console.log('Not a login/register transition, allowing normal navigation');
                }
            });
        });
        
        // Ensure form submissions are NEVER intercepted
        const forms = document.querySelectorAll('form');
        console.log('Found forms:', forms.length);
        
        forms.forEach((form, index) => {
            console.log(`Form ${index + 1} action:`, form.action);
            
            // Remove any existing event listeners that might interfere
            form.addEventListener('submit', (e) => {
                console.log('Form submission detected for:', form.action);
                // Reset transition flag to ensure no interference
                this.isTransitioning = false;
                // Don't prevent default - let form submit normally
            }, { passive: true });
        });
        
        // Debug: Log which links are being tracked
        console.log('Page transition tracking enabled for:', navLinks.length, 'navigation links');
        navLinks.forEach(link => {
            console.log('- Tracking link:', link.href, link.textContent.trim());
        });
    }

    transitionToPage(targetUrl, fromPath, toPath) {
        if (this.isTransitioning) return;
        
        this.isTransitioning = true;
        
        // Determine exit direction and journey details
        let exitClass = 'page-exit';
        let journeyMessage = 'Kepiting sedang berjalan...';
        let targetIcon = 'login-icon';
        
        if (fromPath.includes('/login') && toPath.includes('/register')) {
            exitClass = 'page-exit-up';
            journeyMessage = 'Menuju halaman pendaftaran...';
            targetIcon = 'register-icon';
        } else if (fromPath.includes('/register') && toPath.includes('/login')) {
            exitClass = 'page-exit-down';
            journeyMessage = 'Menuju halaman masuk...';
            targetIcon = 'login-icon';
        }

        // Get elements
        const loginCard = document.querySelector('.login-card');
        const character = document.querySelector('.character');
        const floatingShapes = document.querySelector('.floating-shapes');

        // Start exit animations
        if (loginCard) {
            loginCard.classList.add('transitioning');
            loginCard.classList.add(exitClass);
        }

        if (character) {
            character.classList.add('transitioning');
            // Character waves goodbye
            setTimeout(() => {
                character.style.transform = 'scale(0.8) rotate(-10deg)';
                character.style.opacity = '0.7';
            }, 200);
        }

        if (floatingShapes) {
            floatingShapes.classList.add('transitioning');
        }

        // Show overlay after initial animation
        setTimeout(() => {
            this.overlay.classList.add('active');
            
            // Update journey message and highlight target icon
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
            
            // Make crab excited when reaching destination
            setTimeout(() => {
                if (walkingCrab) {
                    walkingCrab.classList.add('excited');
                }
                if (messageEl) {
                    messageEl.textContent = 'Hampir sampai!';
                }
            }, 1500);
            
            // Final excitement before transition
            setTimeout(() => {
                if (messageEl) {
                    messageEl.textContent = 'Yeay! Sampai tujuan!';
                }
            }, 2500);
        }, 300);

        // Navigate to new page
        setTimeout(() => {
            window.location.href = targetUrl;
        }, 3200);
    }

    // Method to handle character interactions during transitions
    enhanceCharacterTransitions() {
        const character = window.LoginCharacter ? new window.LoginCharacter() : null;
        
        if (character) {
            // Override character methods to include transition awareness
            const originalSetExpression = character.setExpression;
            character.setExpression = function(expression) {
                if (!document.querySelector('.login-card.transitioning')) {
                    originalSetExpression.call(this, expression);
                }
            };
        }
    }
}

// Initialize page transitions when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page transitions initializing...');
    
    try {
        const pageTransition = new PageTransition();
        console.log('Page transition created successfully');
        
        // Enhance character transitions if character exists
        if (window.LoginCharacter) {
            setTimeout(() => {
                pageTransition.enhanceCharacterTransitions();
            }, 500);
        }
    } catch (error) {
        console.error('Error initializing page transitions:', error);
    }
});

// Export for global access
window.PageTransition = PageTransition;