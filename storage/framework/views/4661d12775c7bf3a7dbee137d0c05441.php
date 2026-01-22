<nav id="navbar" class="glass-navbar fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            
            <a href="/" class="flex items-center gap-2 sm:gap-3">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo Puskesmas"
                    class="w-10 h-10 lg:w-12 lg:h-12 object-contain">
                <div>
                    <span class="text-sm sm:text-lg lg:text-xl font-bold text-neutral-900">Puskesmas</span>
                    <span
                        class="text-[10px] sm:text-xs lg:text-sm text-neutral-500 block -mt-0.5 sm:-mt-1 max-w-[140px] sm:max-w-none leading-tight">Rawat
                        Inap Kabalsiang Benjuring</span>
                </div>
            </a>

            
            <div class="hidden lg:flex items-center gap-1">
                
                <a href="/"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-all <?php echo e(request()->is('/') ? 'bg-primary-100 text-primary-700' : 'text-neutral-700 hover:text-primary-600 hover:bg-primary-50'); ?>">
                    Beranda
                </a>

                
                <?php
                    $isKlasterActive = request()->is('klaster-*') || request()->is('lintas-klaster');
                ?>
                <div class="relative" data-dropdown>
                    <button
                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all flex items-center gap-1 <?php echo e($isKlasterActive ? 'bg-primary-100 text-primary-700' : 'text-neutral-700 hover:text-primary-600 hover:bg-primary-50'); ?>"
                        data-dropdown-toggle>
                        Layanan Klaster
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform"></i>
                    </button>
                    <div class="dropdown-menu absolute top-full left-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-neutral-200 opacity-0 invisible transition-all duration-200 z-50"
                        data-dropdown-menu>
                        <div class="p-2">
                            <a href="/klaster-1"
                                class="flex items-center gap-3 p-3 rounded-lg transition-colors <?php echo e(request()->is('klaster-1') ? 'bg-primary-50' : 'hover:bg-neutral-50'); ?>">
                                <div
                                    class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <i data-lucide="settings" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-semibold <?php echo e(request()->is('klaster-1') ? 'text-primary-700' : 'text-neutral-900'); ?>">Klaster
                                        1: Manajemen</span>
                                    <span class="text-xs text-neutral-500 block">Tata Kelola & Administrasi</span>
                                </div>
                            </a>
                            <a href="/klaster-2"
                                class="flex items-center gap-3 p-3 rounded-lg transition-colors <?php echo e(request()->is('klaster-2') ? 'bg-primary-50' : 'hover:bg-neutral-50'); ?>">
                                <div
                                    class="w-10 h-10 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center">
                                    <i data-lucide="baby" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-semibold <?php echo e(request()->is('klaster-2') ? 'text-primary-700' : 'text-neutral-900'); ?>">Klaster
                                        2: Ibu & Anak</span>
                                    <span class="text-xs text-neutral-500 block">KIA, Imunisasi, Gizi</span>
                                </div>
                            </a>
                            <a href="/klaster-3"
                                class="flex items-center gap-3 p-3 rounded-lg transition-colors <?php echo e(request()->is('klaster-3') ? 'bg-primary-50' : 'hover:bg-neutral-50'); ?>">
                                <div
                                    class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                                    <i data-lucide="users" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-semibold <?php echo e(request()->is('klaster-3') ? 'text-primary-700' : 'text-neutral-900'); ?>">Klaster
                                        3: Dewasa & Lansia</span>
                                    <span class="text-xs text-neutral-500 block">PTM, Kesehatan Jiwa, Lansia</span>
                                </div>
                            </a>
                            <a href="/klaster-4"
                                class="flex items-center gap-3 p-3 rounded-lg transition-colors <?php echo e(request()->is('klaster-4') ? 'bg-primary-50' : 'hover:bg-neutral-50'); ?>">
                                <div
                                    class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-semibold <?php echo e(request()->is('klaster-4') ? 'text-primary-700' : 'text-neutral-900'); ?>">Klaster
                                        4: P2P & Kesling</span>
                                    <span class="text-xs text-neutral-500 block">Pencegahan Penyakit Menular</span>
                                </div>
                            </a>
                            <a href="/lintas-klaster"
                                class="flex items-center gap-3 p-3 rounded-lg transition-colors <?php echo e(request()->is('lintas-klaster') ? 'bg-primary-50' : 'hover:bg-neutral-50'); ?>">
                                <div
                                    class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center">
                                    <i data-lucide="layers" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-semibold <?php echo e(request()->is('lintas-klaster') ? 'text-primary-700' : 'text-neutral-900'); ?>">Lintas
                                        Klaster</span>
                                    <span class="text-xs text-neutral-500 block">UGD, Farmasi, Laboratorium</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                
                <?php
                    $isProfilActive = request()->is('struktur-organisasi');
                ?>
                <div class="relative" data-dropdown>
                    <button
                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all flex items-center gap-1 <?php echo e($isProfilActive ? 'bg-primary-100 text-primary-700' : 'text-neutral-700 hover:text-primary-600 hover:bg-primary-50'); ?>"
                        data-dropdown-toggle>
                        Profil
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform"></i>
                    </button>
                    <div class="dropdown-menu absolute top-full left-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-neutral-200 opacity-0 invisible transition-all duration-200 z-50"
                        data-dropdown-menu>
                        <div class="p-2">
                            <a href="/#tentang"
                                class="flex items-center gap-3 p-3 rounded-lg hover:bg-neutral-50 transition-colors">
                                <div
                                    class="w-8 h-8 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center">
                                    <i data-lucide="info" class="w-4 h-4"></i>
                                </div>
                                <span class="text-sm font-medium text-neutral-700">Tentang Kami</span>
                            </a>
                            <a href="/struktur-organisasi"
                                class="flex items-center gap-3 p-3 rounded-lg transition-colors <?php echo e(request()->is('struktur-organisasi') ? 'bg-primary-50' : 'hover:bg-neutral-50'); ?>">
                                <div
                                    class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                    <i data-lucide="git-branch" class="w-4 h-4"></i>
                                </div>
                                <span
                                    class="text-sm font-medium <?php echo e(request()->is('struktur-organisasi') ? 'text-primary-700' : 'text-neutral-700'); ?>">Struktur
                                    Organisasi</span>
                            </a>
                        </div>
                    </div>
                </div>

                
                <a href="/berita"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-all <?php echo e(request()->is('berita*') ? 'bg-primary-100 text-primary-700' : 'text-neutral-700 hover:text-primary-600 hover:bg-primary-50'); ?>">
                    Berita
                </a>

                
                <a href="/galeri"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-all <?php echo e(request()->is('galeri*') ? 'bg-primary-100 text-primary-700' : 'text-neutral-700 hover:text-primary-600 hover:bg-primary-50'); ?>">
                    Galeri
                </a>

                
                <a href="#kontak"
                    class="px-4 py-2 text-sm font-medium text-neutral-700 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">
                    Kontak
                </a>
            </div>

            
            <div class="hidden lg:flex items-center gap-3">
                <a href="/hubungi-kami" class="btn btn-primary">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                    Hubungi Kami
                </a>
            </div>

            
            <button id="mobile-menu-btn"
                class="lg:hidden p-2 rounded-lg hover:bg-neutral-100 transition-colors relative w-10 h-10 flex items-center justify-center">
                <i data-lucide="menu" class="w-6 h-6 text-neutral-700 mobile-icon-menu transition-all duration-300"></i>
                <i data-lucide="x"
                    class="w-6 h-6 text-neutral-700 mobile-icon-close absolute opacity-0 scale-75 transition-all duration-300"></i>
            </button>
        </div>
    </div>

    
    <div id="mobile-menu"
        class="lg:hidden hidden glass-mobile border-t border-neutral-200/50 transition-all duration-300 ease-in-out overflow-y-auto"
        style="max-height: 0;" x-data="{ 
            openSection: null,
            toggle(section) {
                this.openSection = this.openSection === section ? null : section;
            }
        }">
        <div class="px-4 py-4 space-y-1">
            
            <a href="/"
                class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors <?php echo e(request()->is('/') ? 'bg-primary-100 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50'); ?>">
                <i data-lucide="home" class="w-5 h-5 text-primary-600"></i>
                Beranda
            </a>

            
            <?php
                $isKlasterActive = request()->is('klaster-*') || request()->is('lintas-klaster');
            ?>
            <div class="border-t border-neutral-100 pt-2 mt-2">
                <button @click="toggle('klaster')"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors <?php echo e($isKlasterActive ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50'); ?>">
                    <div class="flex items-center gap-3">
                        <i data-lucide="layers" class="w-5 h-5 text-emerald-600"></i>
                        Layanan Klaster
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200"
                        :class="openSection === 'klaster' ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="openSection === 'klaster'" x-collapse
                    class="ml-4 pl-4 border-l-2 border-neutral-200 space-y-1 mt-1">
                    <a href="/klaster-1"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-colors <?php echo e(request()->is('klaster-1') ? 'bg-primary-100 text-primary-700 font-medium' : 'text-neutral-600 hover:bg-neutral-50'); ?>">
                        <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i data-lucide="settings" class="w-3.5 h-3.5"></i>
                        </div>
                        Klaster 1: Manajemen
                    </a>
                    <a href="/klaster-2"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-colors <?php echo e(request()->is('klaster-2') ? 'bg-primary-100 text-primary-700 font-medium' : 'text-neutral-600 hover:bg-neutral-50'); ?>">
                        <div class="w-7 h-7 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center">
                            <i data-lucide="baby" class="w-3.5 h-3.5"></i>
                        </div>
                        Klaster 2: Ibu & Anak
                    </a>
                    <a href="/klaster-3"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-colors <?php echo e(request()->is('klaster-3') ? 'bg-primary-100 text-primary-700 font-medium' : 'text-neutral-600 hover:bg-neutral-50'); ?>">
                        <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                            <i data-lucide="users" class="w-3.5 h-3.5"></i>
                        </div>
                        Klaster 3: Dewasa & Lansia
                    </a>
                    <a href="/klaster-4"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-colors <?php echo e(request()->is('klaster-4') ? 'bg-primary-100 text-primary-700 font-medium' : 'text-neutral-600 hover:bg-neutral-50'); ?>">
                        <div class="w-7 h-7 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        </div>
                        Klaster 4: P2P & Kesling
                    </a>
                    <a href="/lintas-klaster"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-colors <?php echo e(request()->is('lintas-klaster') ? 'bg-primary-100 text-primary-700 font-medium' : 'text-neutral-600 hover:bg-neutral-50'); ?>">
                        <div
                            class="w-7 h-7 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center">
                            <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                        </div>
                        Lintas Klaster
                    </a>
                </div>
            </div>

            
            <?php
                $isProfilActive = request()->is('struktur-organisasi');
            ?>
            <div>
                <button @click="toggle('profil')"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors <?php echo e($isProfilActive ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50'); ?>">
                    <div class="flex items-center gap-3">
                        <i data-lucide="building-2" class="w-5 h-5 text-indigo-600"></i>
                        Profil
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200"
                        :class="openSection === 'profil' ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="openSection === 'profil'" x-collapse
                    class="ml-4 pl-4 border-l-2 border-neutral-200 space-y-1 mt-1">
                    <a href="/#tentang"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-neutral-600 hover:bg-neutral-50 rounded-lg transition-colors">
                        <i data-lucide="info" class="w-4 h-4 text-primary-600"></i>
                        Tentang Kami
                    </a>
                    <a href="/struktur-organisasi"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-colors <?php echo e(request()->is('struktur-organisasi') ? 'bg-primary-100 text-primary-700 font-medium' : 'text-neutral-600 hover:bg-neutral-50'); ?>">
                        <i data-lucide="git-branch" class="w-4 h-4 text-indigo-600"></i>
                        Struktur Organisasi
                    </a>
                </div>
            </div>

            
            <?php
                $isKontenActive = request()->is('berita*') || request()->is('galeri*');
            ?>
            <div>
                <button @click="toggle('konten')"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors <?php echo e($isKontenActive ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50'); ?>">
                    <div class="flex items-center gap-3">
                        <i data-lucide="file-text" class="w-5 h-5 text-violet-600"></i>
                        Konten
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200"
                        :class="openSection === 'konten' ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="openSection === 'konten'" x-collapse
                    class="ml-4 pl-4 border-l-2 border-neutral-200 space-y-1 mt-1">
                    <a href="/berita"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-colors <?php echo e(request()->is('berita*') ? 'bg-primary-100 text-primary-700 font-medium' : 'text-neutral-600 hover:bg-neutral-50'); ?>">
                        <i data-lucide="newspaper" class="w-4 h-4 text-emerald-600"></i>
                        Berita & Kegiatan
                    </a>
                    <a href="/galeri"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-colors <?php echo e(request()->is('galeri*') ? 'bg-primary-100 text-primary-700 font-medium' : 'text-neutral-600 hover:bg-neutral-50'); ?>">
                        <i data-lucide="images" class="w-4 h-4 text-violet-600"></i>
                        Galeri
                    </a>
                </div>
            </div>

            
            <a href="/hubungi-kami"
                class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors border-t border-neutral-100 mt-2 pt-2 <?php echo e(request()->is('hubungi-kami') ? 'bg-primary-100 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50'); ?>">
                <i data-lucide="mail" class="w-5 h-5 text-sky-600"></i>
                Hubungi Kami
            </a>

            
            <div class="pt-4 pb-2 border-t border-neutral-100 mt-3">
                <a href="/hubungi-kami" class="btn btn-primary w-full justify-center">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Kirim Pesan
                </a>
            </div>
        </div>
    </div>
</nav><?php /**PATH C:\laragon\www\webpkm2026\resources\views/components/navbar.blade.php ENDPATH**/ ?>