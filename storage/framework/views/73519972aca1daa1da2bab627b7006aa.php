

<?php $__env->startSection('title', $post->title . ' - Berita Puskesmas'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="pt-24 lg:pt-28 bg-neutral-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center gap-2 text-sm">
                <a href="/" class="text-neutral-500 hover:text-primary-600">Beranda</a>
                <i data-lucide="chevron-right" class="w-4 h-4 text-neutral-400"></i>
                <a href="<?php echo e(route('berita.index')); ?>" class="text-neutral-500 hover:text-primary-600">Berita</a>
                <i data-lucide="chevron-right" class="w-4 h-4 text-neutral-400"></i>
                <span class="text-neutral-900 truncate max-w-xs"><?php echo e($post->title); ?></span>
            </nav>
        </div>
    </div>

    
    <article class="py-8 lg:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <header class="mb-8">
                <?php if($post->category): ?>
                    <span class="inline-block px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm font-medium mb-4">
                        <?php echo e($post->category->name); ?>

                    </span>
                <?php endif; ?>

                <h1 class="text-3xl lg:text-4xl font-bold text-neutral-900 leading-tight mb-4">
                    <?php echo e($post->title); ?>

                </h1>

                <div class="flex flex-wrap items-center gap-4 text-sm text-neutral-500">
                    <span class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <?php echo e($post->formatted_date); ?>

                    </span>
                    <?php if($post->author): ?>
                        <span class="flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            <?php echo e($post->author); ?>

                        </span>
                    <?php endif; ?>
                    <span class="flex items-center gap-2">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                        <?php echo e(number_format($post->views_count)); ?> views
                    </span>
                </div>
            </header>

            
            <?php if($post->featured_image): ?>
                <figure class="mb-8 rounded-2xl overflow-hidden">
                    <img src="<?php echo e($post->featured_image_url); ?>" 
                         alt="<?php echo e($post->title); ?>" 
                         class="w-full max-h-[500px] object-cover">
                </figure>
            <?php endif; ?>

            
            <div class="prose prose-lg max-w-none prose-headings:text-neutral-900 prose-p:text-neutral-600 prose-a:text-primary-600 prose-img:rounded-xl">
                <?php echo $post->content; ?>

            </div>

            
            <div class="mt-10 pt-8 border-t border-neutral-200">
                <p class="text-sm font-medium text-neutral-900 mb-3">Bagikan artikel ini:</p>
                <div class="flex gap-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(request()->url())); ?>" 
                       target="_blank"
                       class="p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo e(urlencode(request()->url())); ?>&text=<?php echo e(urlencode($post->title)); ?>" 
                       target="_blank"
                       class="p-3 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="https://wa.me/?text=<?php echo e(urlencode($post->title . ' ' . request()->url())); ?>" 
                       target="_blank"
                       class="p-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i data-lucide="message-circle" class="w-5 h-5"></i>
                    </a>
                    <button onclick="navigator.clipboard.writeText('<?php echo e(request()->url()); ?>'); alert('Link berhasil disalin!')"
                            class="p-3 bg-neutral-200 text-neutral-700 rounded-lg hover:bg-neutral-300 transition-colors">
                        <i data-lucide="link" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
        </div>
    </article>

    
    <?php if($relatedPosts->count() > 0): ?>
        <section class="py-12 bg-neutral-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-neutral-900 mb-6">Berita Terkait</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $relatedPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <article class="card overflow-hidden group">
                            <a href="<?php echo e(route('berita.show', $related)); ?>" class="block">
                                <div class="aspect-video relative overflow-hidden">
                                    <?php if($related->featured_image): ?>
                                        <img src="<?php echo e($related->featured_image_url); ?>" 
                                             alt="<?php echo e($related->title); ?>" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    <?php else: ?>
                                        <div class="w-full h-full bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                                            <i data-lucide="newspaper" class="w-10 h-10 text-primary-400"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="p-4">
                                    <time class="text-xs text-neutral-500"><?php echo e($related->formatted_date); ?></time>
                                    <h3 class="font-semibold text-neutral-900 mt-1 line-clamp-2 group-hover:text-primary-600 transition-colors">
                                        <?php echo e($related->title); ?>

                                    </h3>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="<?php echo e(route('berita.index')); ?>" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali ke Daftar Berita
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\webpkm2026\resources\views/berita/show.blade.php ENDPATH**/ ?>