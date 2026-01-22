

<?php $__env->startSection('title', 'Galeri Kegiatan - Puskesmas'); ?>

<?php $__env->startSection('content'); ?>
    
    <section class="pt-24 lg:pt-32 pb-12 bg-gradient-to-br from-primary-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl lg:text-4xl font-bold text-neutral-900 mb-4">Galeri Kegiatan</h1>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">
                Dokumentasi foto kegiatan Puskesmas Rawat Inap Kabalsiang Benjuring
            </p>
        </div>
    </section>

    
    <section class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 overflow-x-auto">
                <div class="flex gap-2 pb-2 justify-center">
                    <a href="<?php echo e(route('galeri.index')); ?>"
                        class="whitespace-nowrap px-5 py-2.5 rounded-full text-sm font-medium transition-colors <?php echo e(!request('kategori') ? 'bg-primary-600 text-white' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200'); ?>">
                        Semua Foto
                    </a>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('galeri.index', ['kategori' => $category->slug])); ?>"
                            class="whitespace-nowrap px-5 py-2.5 rounded-full text-sm font-medium transition-colors <?php echo e(request('kategori') === $category->slug ? 'bg-primary-600 text-white' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200'); ?>">
                            <?php echo e($category->name); ?> (<?php echo e($category->galleries_count); ?>)
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <?php if($galleries->count() > 0): ?>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4" x-data="{ lightbox: null }">
                    <?php $__currentLoopData = $galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="group cursor-pointer" @click="lightbox = <?php echo e($index); ?>">
                            <div class="aspect-square rounded-xl overflow-hidden relative">
                                <img src="<?php echo e($gallery->image_url); ?>" alt="<?php echo e($gallery->title); ?>"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                    <div>
                                        <p class="text-white font-medium text-sm line-clamp-2"><?php echo e($gallery->title); ?></p>
                                        <?php if($gallery->event_date): ?>
                                            <p class="text-white/80 text-xs mt-1"><?php echo e($gallery->formatted_date); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <div x-show="lightbox !== null" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
                        @keydown.escape.window="lightbox = null" x-cloak>

                        
                        <button @click="lightbox = null" class="absolute top-4 right-4 p-2 text-white/80 hover:text-white">
                            <i data-lucide="x" class="w-8 h-8"></i>
                        </button>

                        
                        <button @click="lightbox = (lightbox - 1 + <?php echo e($galleries->count()); ?>) % <?php echo e($galleries->count()); ?>"
                            class="absolute left-4 p-2 text-white/80 hover:text-white">
                            <i data-lucide="chevron-left" class="w-10 h-10"></i>
                        </button>
                        <button @click="lightbox = (lightbox + 1) % <?php echo e($galleries->count()); ?>"
                            class="absolute right-4 p-2 text-white/80 hover:text-white">
                            <i data-lucide="chevron-right" class="w-10 h-10"></i>
                        </button>

                        
                        <div class="max-w-5xl max-h-[80vh] w-full">
                            <?php $__currentLoopData = $galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div x-show="lightbox === <?php echo e($index); ?>" x-transition class="text-center">
                                    <img src="<?php echo e($gallery->image_url); ?>" alt="<?php echo e($gallery->title); ?>"
                                        class="max-w-full max-h-[70vh] mx-auto rounded-lg object-contain">
                                    <div class="mt-4 text-white">
                                        <p class="text-lg font-medium"><?php echo e($gallery->title); ?></p>
                                        <?php if($gallery->description): ?>
                                            <p class="text-white/70 mt-1"><?php echo e($gallery->description); ?></p>
                                        <?php endif; ?>
                                        <?php if($gallery->event_date): ?>
                                            <p class="text-white/50 text-sm mt-2"><?php echo e($gallery->formatted_date); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                
                <div class="mt-10">
                    <?php echo e($galleries->links()); ?>

                </div>
            <?php else: ?>
                <div class="card p-12 text-center">
                    <i data-lucide="image" class="w-16 h-16 mx-auto mb-4 text-neutral-300"></i>
                    <h3 class="text-xl font-semibold text-neutral-900 mb-2">Belum Ada Foto</h3>
                    <p class="text-neutral-600">Galeri foto kegiatan akan segera hadir.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\webpkm2026\resources\views/galeri/index.blade.php ENDPATH**/ ?>