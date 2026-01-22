

<?php $__env->startSection('title', 'Puskesmas - Pusat Kesehatan Masyarakat'); ?>

<?php $__env->startSection('content'); ?>
    
    <section class="relative min-h-screen flex items-center pt-20 lg:pt-0 overflow-hidden">
        
        <div class="absolute inset-0 gradient-hero-soft"></div>
        <div class="absolute top-0 right-0 w-1/2 h-full opacity-30">
            <div class="absolute top-20 right-20 w-72 h-72 bg-primary-200 rounded-full blur-3xl"></div>
            <div class="absolute bottom-40 right-40 w-96 h-96 bg-accent-200 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-24 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                
                <div class="animate-fade-in-up">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 bg-primary-100 rounded-full text-primary-700 text-sm font-medium mb-6">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                        Integrasi Layanan Primer (ILP)
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-neutral-900 leading-tight mb-6">
                        Kesehatan Anda,
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-accent-600">
                            Prioritas Kami
                        </span>
                    </h1>
                    <p class="text-lg text-neutral-600 leading-relaxed mb-8 max-w-xl">
                        Puskesmas hadir memberikan pelayanan kesehatan terpadu dan berkualitas untuk seluruh lapisan
                        masyarakat. Dari ibu hamil hingga lansia, kami siap melayani dengan sepenuh hati.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#layanan" class="btn btn-primary text-base px-6 py-3">
                            <i data-lucide="stethoscope" class="w-5 h-5"></i>
                            Lihat Layanan
                        </a>
                        <a href="#kontak" class="btn btn-secondary text-base px-6 py-3">
                            <i data-lucide="phone" class="w-5 h-5"></i>
                            Hubungi Kami
                        </a>
                    </div>

                    
                    <div class="grid grid-cols-3 gap-6 mt-12 pt-8 border-t border-neutral-200">
                        <div>
                            <span class="text-3xl font-bold text-primary-600">5</span>
                            <p class="text-sm text-neutral-500 mt-1">Klaster Layanan</p>
                        </div>
                        <div>
                            <span class="text-3xl font-bold text-primary-600">24/7</span>
                            <p class="text-sm text-neutral-500 mt-1">UGD Siaga</p>
                        </div>
                        <div>
                            <span class="text-3xl font-bold text-primary-600">50+</span>
                            <p class="text-sm text-neutral-500 mt-1">Tenaga Medis</p>
                        </div>
                    </div>
                </div>

                
                <div class="hidden lg:block relative">
                    <div class="relative w-full h-[500px] rounded-3xl overflow-hidden shadow-2xl border border-white/50">
                        <img src="<?php echo e(asset('images/puskesmas.png')); ?>" alt="Puskesmas Rawat Inap Kabalsiang Benjuring"
                            class="w-full h-full object-cover">
                    </div>
                    
                    <div
                        class="absolute -left-8 top-20 bg-white p-4 rounded-xl shadow-xl border border-neutral-100 animate-bounce-slow">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                                <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-neutral-900">Terakreditasi</p>
                                <p class="text-sm text-neutral-500">Kemenkes RI</p>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -right-4 bottom-32 bg-white p-4 rounded-xl shadow-xl border border-neutral-100">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                <i data-lucide="clock" class="w-6 h-6 text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-neutral-900">Buka Setiap Hari</p>
                                <p class="text-sm text-neutral-500">07:30 - 14:30 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section id="layanan" class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
                <span
                    class="inline-block px-4 py-1.5 bg-primary-100 text-primary-700 text-sm font-medium rounded-full mb-4">
                    Layanan Kami
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                    5 Klaster Pelayanan Terintegrasi
                </h2>
                <p class="text-neutral-600 leading-relaxed">
                    Sesuai Permenkes Nomor 19 Tahun 2024 tentang Integrasi Layanan Primer (ILP), Puskesmas menyediakan
                    pelayanan kesehatan komprehensif dalam 5 klaster utama.
                </p>
            </div>

            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                
                <a href="/klaster-1" class="group relative rounded-2xl overflow-hidden h-72 card-hover">
                    <img src="<?php echo e(asset('images/klaster1-desktop.png')); ?>" alt="Klaster 1"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-900/90 via-blue-900/50 to-transparent"></div>
                    <div class="absolute inset-0 p-6 flex flex-col justify-end">
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-500/80 backdrop-blur-sm text-white flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i data-lucide="settings" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Klaster 1: Manajemen</h3>
                        <p class="text-blue-100 text-sm leading-relaxed mb-3 line-clamp-2">
                            Tata kelola administrasi, kepegawaian, keuangan, dan manajemen mutu.
                        </p>
                        <span
                            class="inline-flex items-center text-white text-sm font-medium group-hover:gap-2 transition-all">
                            Selengkapnya
                            <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                        </span>
                    </div>
                </a>

                
                <a href="/klaster-2" class="group relative rounded-2xl overflow-hidden h-72 card-hover">
                    <img src="<?php echo e(asset('images/klaster2-desktop.png')); ?>" alt="Klaster 2"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-pink-900/90 via-pink-900/50 to-transparent"></div>
                    <div class="absolute inset-0 p-6 flex flex-col justify-end">
                        <div
                            class="w-12 h-12 rounded-xl bg-pink-500/80 backdrop-blur-sm text-white flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i data-lucide="baby" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Klaster 2: Ibu & Anak</h3>
                        <p class="text-pink-100 text-sm leading-relaxed mb-3 line-clamp-2">
                            Pelayanan ibu hamil, bersalin, nifas, bayi, balita, dan remaja.
                        </p>
                        <span
                            class="inline-flex items-center text-white text-sm font-medium group-hover:gap-2 transition-all">
                            Selengkapnya
                            <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                        </span>
                    </div>
                </a>

                
                <a href="/klaster-3" class="group relative rounded-2xl overflow-hidden h-72 card-hover">
                    <img src="<?php echo e(asset('images/klaster3-desktop.png')); ?>" alt="Klaster 3"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-amber-900/90 via-amber-900/50 to-transparent"></div>
                    <div class="absolute inset-0 p-6 flex flex-col justify-end">
                        <div
                            class="w-12 h-12 rounded-xl bg-amber-500/80 backdrop-blur-sm text-white flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i data-lucide="users" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Klaster 3: Dewasa & Lansia</h3>
                        <p class="text-amber-100 text-sm leading-relaxed mb-3 line-clamp-2">
                            Pelayanan kesehatan usia dewasa dan lanjut usia, termasuk PTM.
                        </p>
                        <span
                            class="inline-flex items-center text-white text-sm font-medium group-hover:gap-2 transition-all">
                            Selengkapnya
                            <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                        </span>
                    </div>
                </a>

                
                <a href="/klaster-4" class="group relative rounded-2xl overflow-hidden h-72 card-hover">
                    <img src="<?php echo e(asset('images/klaster4-desktop.png')); ?>" alt="Klaster 4"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-red-900/90 via-red-900/50 to-transparent"></div>
                    <div class="absolute inset-0 p-6 flex flex-col justify-end">
                        <div
                            class="w-12 h-12 rounded-xl bg-red-500/80 backdrop-blur-sm text-white flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Klaster 4: P2P & Kesling</h3>
                        <p class="text-red-100 text-sm leading-relaxed mb-3 line-clamp-2">
                            Penanggulangan penyakit menular dan pengawasan kualitas lingkungan.
                        </p>
                        <span
                            class="inline-flex items-center text-white text-sm font-medium group-hover:gap-2 transition-all">
                            Selengkapnya
                            <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                        </span>
                    </div>
                </a>

                
                <a href="/lintas-klaster" class="group relative rounded-2xl overflow-hidden h-72 md:col-span-2 card-hover">
                    <img src="<?php echo e(asset('images/lintasklaster-desktop.png')); ?>" alt="Lintas Klaster"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary-900/95 via-primary-900/70 to-primary-900/40">
                    </div>
                    <div class="absolute inset-0 p-6 lg:p-8 flex flex-col lg:flex-row lg:items-center gap-4 lg:gap-8">
                        <div
                            class="w-14 h-14 rounded-xl bg-primary-500/80 backdrop-blur-sm text-white flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                            <i data-lucide="layers" class="w-7 h-7"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-xl lg:text-2xl font-bold text-white mb-2">Lintas Klaster</h3>
                            <p class="text-primary-100 text-sm lg:text-base leading-relaxed max-w-xl">
                                Unit Gawat Darurat (UGD) 24 Jam, Pelayanan Kefarmasian, Laboratorium Klinik, dan Rawat Inap
                                untuk mendukung pelayanan terintegrasi.
                            </p>
                        </div>
                        <span
                            class="inline-flex items-center text-white text-sm font-medium lg:flex-shrink-0 group-hover:gap-2 transition-all">
                            Selengkapnya
                            <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    
    <section id="tentang" class="py-16 lg:py-24 bg-neutral-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                
                <div class="relative">
                    <div
                        class="aspect-square max-w-md mx-auto lg:max-w-none rounded-3xl overflow-hidden shadow-xl border border-neutral-200">
                        <img src="<?php echo e(asset('images/peta.png')); ?>" alt="Puskesmas Rawat Inap Kabalsiang Benjuring"
                            class="w-full h-full object-cover">
                    </div>
                    
                    <div
                        class="absolute -bottom-6 -right-6 bg-white p-6 rounded-2xl shadow-xl border border-neutral-100 hidden lg:block">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-16 h-16 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg">
                                <i data-lucide="award" class="w-8 h-8 text-white"></i>
                            </div>
                            <div>
                                <p class="text-xl font-bold text-neutral-900">Akreditasi</p>
                                <p class="text-lg font-semibold text-amber-600">UTAMA</p>
                            </div>
                        </div>
                    </div>
                    
                    <div
                        class="absolute -top-4 -left-4 bg-white px-4 py-3 rounded-xl shadow-lg border border-neutral-100 hidden lg:flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-5 h-5 text-primary-600"></i>
                        <span class="text-sm font-medium text-neutral-700">Kepulauan Aru</span>
                    </div>
                </div>

                
                <div>
                    <span
                        class="inline-block px-4 py-1.5 bg-primary-100 text-primary-700 text-sm font-medium rounded-full mb-4">
                        Tentang Kami
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-6">
                        Melayani dengan Sepenuh Hati
                    </h2>

                    <p class="text-neutral-600 leading-relaxed mb-5">
                        <strong class="text-neutral-800">Puskesmas Rawat Inap Kabalsiang Benjuring</strong> merupakan
                        Unit Pelaksana Teknis Daerah (UPTD) yang menyelenggarakan pelayanan kesehatan primer bagi
                        masyarakat <strong class="text-neutral-800">Kecamatan Aru Utara Timur Batuley</strong>.
                        Kami melayani <strong class="text-primary-600">5 desa</strong> dengan jumlah penduduk sekitar
                        <strong class="text-primary-600">3.188 jiwa</strong>.
                    </p>

                    <p class="text-neutral-600 leading-relaxed mb-5">
                        Sebagai Puskesmas Rawat Inap dengan status <strong class="text-amber-600">Akreditasi Utama</strong>,
                        kami menyediakan pelayanan kesehatan yang komprehensif, meliputi upaya promotif, preventif,
                        kuratif, dan rehabilitatif, sesuai dengan standar Kementerian Kesehatan Republik Indonesia.
                    </p>

                    <p class="text-neutral-600 leading-relaxed mb-8">
                        Dengan menerapkan <strong class="text-neutral-800">Integrasi Layanan Primer (ILP)</strong>,
                        kami berkomitmen memberikan pelayanan kesehatan berkesinambungan bagi seluruh siklus kehidupan —
                        dari ibu dan anak, remaja, dewasa, hingga lanjut usia — dengan pendekatan yang ramah, profesional,
                        dan <em>berorientasi pada kebutuhan masyarakat pesisir dan kepulauan</em>.
                    </p>

                    
                    <div class="relative mb-8 p-4 bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
                        
                        <div class="absolute inset-0 md:hidden">
                            <img src="<?php echo e(asset('images/peta.png')); ?>" alt="Peta Wilayah"
                                class="w-full h-full object-cover opacity-10">
                        </div>
                        
                        <div class="relative z-10 grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div class="text-center p-3">
                                <div
                                    class="w-10 h-10 mx-auto rounded-lg bg-primary-100 flex items-center justify-center mb-2">
                                    <i data-lucide="map" class="w-5 h-5 text-primary-600"></i>
                                </div>
                                <p class="text-xs text-neutral-500 mb-1">Wilayah Kerja</p>
                                <p class="text-sm font-semibold text-neutral-900">5 Desa</p>
                                <p class="text-xs text-neutral-500">Kec. Aru Utara Timur Batuley</p>
                            </div>
                            <div class="text-center p-3">
                                <div class="w-10 h-10 mx-auto rounded-lg bg-blue-100 flex items-center justify-center mb-2">
                                    <i data-lucide="bed" class="w-5 h-5 text-blue-600"></i>
                                </div>
                                <p class="text-xs text-neutral-500 mb-1">Status Layanan</p>
                                <p class="text-sm font-semibold text-neutral-900">Rawat Inap</p>
                                <p class="text-xs text-neutral-500">+ UGD 24 Jam</p>
                            </div>
                            <div class="text-center p-3 col-span-2 md:col-span-1">
                                <div
                                    class="w-10 h-10 mx-auto rounded-lg bg-amber-100 flex items-center justify-center mb-2">
                                    <i data-lucide="award" class="w-5 h-5 text-amber-600"></i>
                                </div>
                                <p class="text-xs text-neutral-500 mb-1">Akreditasi</p>
                                <p class="text-sm font-semibold text-amber-600">UTAMA</p>
                                <p class="text-xs text-neutral-500">Kemenkes RI</p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="check" class="w-5 h-5 text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-neutral-900">Terakreditasi Utama</p>
                                <p class="text-sm text-neutral-500">Standar Kemenkes RI</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-neutral-900">Tim Profesional</p>
                                <p class="text-sm text-neutral-500">Dokter & Tenaga Medis</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-cyan-100 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="anchor" class="w-5 h-5 text-cyan-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-neutral-900">Wilayah Kepulauan</p>
                                <p class="text-sm text-neutral-500">Melayani Pesisir & Pulau</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="wallet" class="w-5 h-5 text-purple-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-neutral-900">Terjangkau</p>
                                <p class="text-sm text-neutral-500">Gratis dengan BPJS</p>
                            </div>
                        </div>
                    </div>
                </div>
    </section>

    
    <?php if(isset($latestPosts) && $latestPosts->count() > 0): ?>
        <section class="py-16 lg:py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
                    <div>
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 bg-primary-100 rounded-full text-primary-700 text-sm font-medium mb-4">
                            <i data-lucide="newspaper" class="w-4 h-4"></i>
                            Berita & Kegiatan
                        </span>
                        <h2 class="text-3xl lg:text-4xl font-bold text-neutral-900">Berita Terbaru</h2>
                        <p class="text-neutral-600 mt-2">Informasi terkini seputar kegiatan dan program Puskesmas</p>
                    </div>
                    <a href="/berita" class="btn btn-secondary shrink-0">
                        <span>Lihat Semua</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    <?php $__currentLoopData = $latestPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <article
                            class="group bg-white rounded-2xl border border-neutral-200 overflow-hidden hover:shadow-xl hover:border-primary-200 transition-all duration-300">
                            
                            <a href="<?php echo e(route('berita.show', $post->slug)); ?>" class="block aspect-[16/10] overflow-hidden relative">
                                <?php if($post->featured_image): ?>
                                    <img src="<?php echo e($post->featured_image_url); ?>" alt="<?php echo e($post->title); ?>"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <?php else: ?>
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                                        <i data-lucide="newspaper" class="w-12 h-12 text-primary-400"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($post->category): ?>
                                    <span
                                        class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-medium text-primary-700">
                                        <?php echo e($post->category->name); ?>

                                    </span>
                                <?php endif; ?>
                            </a>

                            
                            <div class="p-5 lg:p-6">
                                <div class="flex items-center gap-3 text-xs text-neutral-500 mb-3">
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                        <?php echo e($post->created_at->format('d M Y')); ?>

                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        <?php echo e(number_format($post->views_count)); ?>

                                    </span>
                                </div>
                                <h3
                                    class="font-semibold text-neutral-900 group-hover:text-primary-600 transition-colors line-clamp-2 mb-2">
                                    <a href="<?php echo e(route('berita.show', $post->slug)); ?>"><?php echo e($post->title); ?></a>
                                </h3>
                                <p class="text-sm text-neutral-600 line-clamp-2 mb-4"><?php echo e($post->excerpt); ?></p>
                                <a href="<?php echo e(route('berita.show', $post->slug)); ?>"
                                    class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700 group/link">
                                    <span>Baca Selengkapnya</span>
                                    <i data-lucide="arrow-right"
                                        class="w-4 h-4 group-hover/link:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if(isset($latestGalleries) && $latestGalleries->count() > 0): ?>
        <section class="py-16 lg:py-24 bg-gradient-to-b from-neutral-50 to-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
                    <div>
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 bg-violet-100 rounded-full text-violet-700 text-sm font-medium mb-4">
                            <i data-lucide="images" class="w-4 h-4"></i>
                            Galeri Foto
                        </span>
                        <h2 class="text-3xl lg:text-4xl font-bold text-neutral-900">Galeri Kegiatan</h2>
                        <p class="text-neutral-600 mt-2">Dokumentasi kegiatan dan momen berharga di Puskesmas</p>
                    </div>
                    <a href="/galeri" class="btn btn-secondary shrink-0">
                        <span>Lihat Semua</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            
            <div class="swiper gallery-swiper" x-data x-init="
                                            new Swiper('.gallery-swiper', {
                                                slidesPerView: 1.2,
                                                spaceBetween: 16,
                                                centeredSlides: true,
                                                loop: true,
                                                autoplay: {
                                                    delay: 3000,
                                                    disableOnInteraction: false,
                                                },
                                                pagination: {
                                                    el: '.swiper-pagination',
                                                    clickable: true,
                                                },
                                                navigation: {
                                                    nextEl: '.swiper-button-next',
                                                    prevEl: '.swiper-button-prev',
                                                },
                                                breakpoints: {
                                                    640: {
                                                        slidesPerView: 2.2,
                                                        spaceBetween: 20,
                                                        centeredSlides: false,
                                                    },
                                                    1024: {
                                                        slidesPerView: 3.5,
                                                        spaceBetween: 24,
                                                        centeredSlides: false,
                                                    },
                                                    1280: {
                                                        slidesPerView: 4,
                                                        spaceBetween: 24,
                                                        centeredSlides: false,
                                                    }
                                                }
                                            })
                                        ">
                <div class="swiper-wrapper pb-12">
                    <?php $__currentLoopData = $latestGalleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="swiper-slide">
                            <div class="group relative aspect-[4/3] rounded-2xl overflow-hidden shadow-lg cursor-pointer"
                                @click="$dispatch('open-lightbox', { src: '<?php echo e($gallery->image_url); ?>', title: '<?php echo e($gallery->title); ?>' })">
                                <img src="<?php echo e($gallery->image_url); ?>" alt="<?php echo e($gallery->title); ?>"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                        <h4 class="font-semibold line-clamp-1"><?php echo e($gallery->title); ?></h4>
                                        <p class="text-sm text-white/80 mt-1"><?php echo e($gallery->created_at->format('d M Y')); ?></p>
                                    </div>
                                </div>
                                
                                <div
                                    class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div
                                        class="w-12 h-12 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center">
                                        <i data-lucide="zoom-in" class="w-5 h-5 text-neutral-700"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <div class="swiper-pagination !relative mt-6"></div>
            </div>
        </section>
    <?php endif; ?>

    
    <div x-data="{ open: false, imageSrc: '', imageTitle: '' }"
        @open-lightbox.window="open = true; imageSrc = $event.detail.src; imageTitle = $event.detail.title"
        @keydown.escape.window="open = false" x-show="open" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4">
        
        <div class="absolute inset-0 bg-black/90 backdrop-blur-sm" @click="open = false" x-show="open"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        
        <div class="relative z-10 max-w-5xl max-h-[90vh] w-full" x-show="open"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
            
            <button @click="open = false" class="absolute -top-12 right-0 text-white/80 hover:text-white transition-colors">
                <i data-lucide="x" class="w-8 h-8"></i>
            </button>
            
            <img :src="imageSrc" :alt="imageTitle" class="w-full h-auto max-h-[80vh] object-contain rounded-lg shadow-2xl">
            
            <p class="text-center text-white/90 mt-4 text-lg font-medium" x-text="imageTitle"></p>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\webpkm2026\resources\views/home.blade.php ENDPATH**/ ?>