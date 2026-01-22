<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Website Resmi Puskesmas - Pusat Kesehatan Masyarakat. Layanan kesehatan terpadu untuk ibu, anak, dewasa, lansia, dan penanggulangan penyakit.">
    <meta name="keywords" content="puskesmas, kesehatan, layanan kesehatan, klinik, pemerintah">
    <title>Puskesmas - Pusat Kesehatan Masyarakat</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <!-- Alpine.js Plugins & Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Swiper.js for Gallery Carousel -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- x-cloak style - hide elements until Alpine loads -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-neutral-50 text-neutral-900">
    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    <script>
        lucide.createIcons();

        // Mobile Menu Toggle with animation
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = mobileMenuBtn?.querySelector('.mobile-icon-menu');
        const closeIcon = mobileMenuBtn?.querySelector('.mobile-icon-close');
        let isMenuOpen = false;

        function toggleMobileMenu(open) {
            isMenuOpen = open;

            if (open) {
                mobileMenu.classList.remove('hidden');
                // Use calc to get proper viewport height minus navbar
                mobileMenu.style.maxHeight = 'calc(100vh - 64px)';
                // Animate icons
                if (menuIcon && closeIcon) {
                    menuIcon.classList.add('opacity-0', 'scale-75', 'rotate-180');
                    closeIcon.classList.remove('opacity-0', 'scale-75');
                    closeIcon.classList.add('rotate-180');
                }
            } else {
                mobileMenu.style.maxHeight = '0px';
                setTimeout(() => mobileMenu.classList.add('hidden'), 300);
                // Animate icons back
                if (menuIcon && closeIcon) {
                    menuIcon.classList.remove('opacity-0', 'scale-75', 'rotate-180');
                    closeIcon.classList.add('opacity-0', 'scale-75');
                    closeIcon.classList.remove('rotate-180');
                }
            }
        }

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                toggleMobileMenu(!isMenuOpen);
            });

            // Close menu when clicking a link
            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    toggleMobileMenu(false);
                });
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (isMenuOpen && !mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                    toggleMobileMenu(false);
                }
            });
        }

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                navbar.classList.add('glass-scrolled');
            } else {
                navbar.classList.remove('glass-scrolled');
            }
        });

        // Dropdown click handling
        document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
            const toggle = dropdown.querySelector('[data-dropdown-toggle]');
            const menu = dropdown.querySelector('[data-dropdown-menu]');
            const chevron = toggle.querySelector('[data-lucide="chevron-down"]');

            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = menu.classList.contains('opacity-100');

                document.querySelectorAll('[data-dropdown-menu]').forEach(m => {
                    m.classList.remove('opacity-100', 'visible');
                    m.classList.add('opacity-0', 'invisible');
                });

                if (!isOpen) {
                    menu.classList.remove('opacity-0', 'invisible');
                    menu.classList.add('opacity-100', 'visible');
                    if (chevron) chevron.style.transform = 'rotate(180deg)';
                } else {
                    if (chevron) chevron.style.transform = 'rotate(0deg)';
                }
            });

            dropdown.addEventListener('mouseenter', () => {
                menu.classList.remove('opacity-0', 'invisible');
                menu.classList.add('opacity-100', 'visible');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            });

            dropdown.addEventListener('mouseleave', () => {
                menu.classList.remove('opacity-100', 'visible');
                menu.classList.add('opacity-0', 'invisible');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            });
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('[data-dropdown]')) {
                document.querySelectorAll('[data-dropdown-menu]').forEach(menu => {
                    menu.classList.remove('opacity-100', 'visible');
                    menu.classList.add('opacity-0', 'invisible');
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>