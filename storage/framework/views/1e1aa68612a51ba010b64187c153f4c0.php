

<?php $__env->startSection('title', 'Berita & Kegiatan - Puskesmas'); ?>

<?php $__env->startSection('content'); ?>
    
    <section class="pt-24 lg:pt-32 pb-12 bg-gradient-to-br from-primary-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-3xl lg:text-4xl font-bold text-neutral-900 mb-4">Berita & Kegiatan</h1>
                <p class="text-lg text-neutral-600">Informasi terbaru seputar kegiatan dan layanan Puskesmas Rawat Inap Kabalsiang Benjuring</p>
            </div>

            
            <div class="mt-8 max-w-2xl mx-auto" x-data="searchComponent()">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400"></i>
                    <input type="text" 
                           x-model="query"
                           @input.debounce.300ms="search()"
                           @focus="showResults = query.length >= 2"
                           @click.outside="showResults = false"
                           placeholder="Cari berita atau kegiatan..."
                           class="w-full pl-12 pr-4 py-4 border border-neutral-200 rounded-2xl shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-lg">
                    <div x-show="loading" class="absolute right-4 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>

                
                <div x-show="showResults && results.length > 0" 
                     x-transition
                     class="absolute z-50 mt-2 w-full max-w-2xl bg-white rounded-xl shadow-xl border border-neutral-200 overflow-hidden"
                     x-cloak>
                    <template x-for="result in results" :key="result.id">
                        <a :href="result.url" class="block p-4 hover:bg-neutral-50 border-b border-neutral-100 last:border-b-0">
                            <h4 class="font-medium text-neutral-900" x-text="result.title"></h4>
                            <p class="text-sm text-neutral-500 mt-1" x-text="result.excerpt"></p>
                            <p class="text-xs text-neutral-400 mt-2" x-text="result.date"></p>
                        </a>
                    </template>
                </div>

                
                <div x-show="showResults && query.length >= 2 && results.length === 0 && !loading"
                     x-transition
                     class="absolute z-50 mt-2 w-full max-w-2xl bg-white rounded-xl shadow-xl border border-neutral-200 p-6 text-center"
                     x-cloak>
                    <i data-lucide="search-x" class="w-10 h-10 mx-auto text-neutral-300 mb-2"></i>
                    <p class="text-neutral-500">Tidak ada hasil untuk "<span x-text="query"></span>"</p>
                </div>
            </div>
        </div>
    </section>

    
    <section class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-4 lg:gap-8">
                
                <aside class="hidden lg:block">
                    <div class="sticky top-24 card p-6">
                        <h3 class="font-semibold text-neutral-900 mb-4">Kategori</h3>
                        <nav class="space-y-2">
                            <a href="<?php echo e(route('berita.index')); ?>" 
                               class="block px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e(!request('kategori') ? 'bg-primary-100 text-primary-700' : 'text-neutral-600 hover:bg-neutral-100'); ?>">
                                Semua
                            </a>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('berita.index', ['kategori' => $category->slug])); ?>" 
                                   class="flex items-center justify-between px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e(request('kategori') === $category->slug ? 'bg-primary-100 text-primary-700' : 'text-neutral-600 hover:bg-neutral-100'); ?>">
                                    <?php echo e($category->name); ?>

                                    <span class="text-xs bg-neutral-100 px-2 py-0.5 rounded-full"><?php echo e($category->posts_count); ?></span>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </nav>
                    </div>
                </aside>

                
                <div class="lg:hidden mb-6 overflow-x-auto">
                    <div class="flex gap-2 pb-2">
                        <a href="<?php echo e(route('berita.index')); ?>" 
                           class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-colors <?php echo e(!request('kategori') ? 'bg-primary-600 text-white' : 'bg-neutral-100 text-neutral-600'); ?>">
                            Semua
                        </a>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('berita.index', ['kategori' => $category->slug])); ?>" 
                               class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-colors <?php echo e(request('kategori') === $category->slug ? 'bg-primary-600 text-white' : 'bg-neutral-100 text-neutral-600'); ?>">
                                <?php echo e($category->name); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div class="lg:col-span-3">
                    <?php if($posts->count() > 0): ?>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <article class="card overflow-hidden group">
                                    <a href="<?php echo e(route('berita.show', $post)); ?>" class="block">
                                        <div class="aspect-video relative overflow-hidden">
                                            <?php if($post->featured_image): ?>
                                                <img src="<?php echo e($post->featured_image_url); ?>" 
                                                     alt="<?php echo e($post->title); ?>" 
                                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                            <?php else: ?>
                                                <div class="w-full h-full bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                                                    <i data-lucide="newspaper" class="w-12 h-12 text-primary-400"></i>
                                                </div>
                                            <?php endif; ?>
                                            <?php if($post->category): ?>
                                                <span class="absolute top-3 left-3 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-medium text-primary-700">
                                                    <?php echo e($post->category->name); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="p-5">
                                            <time class="text-xs text-neutral-500"><?php echo e($post->formatted_date); ?></time>
                                            <h3 class="font-semibold text-neutral-900 mt-2 line-clamp-2 group-hover:text-primary-600 transition-colors">
                                                <?php echo e($post->title); ?>

                                            </h3>
                                            <p class="text-sm text-neutral-600 mt-2 line-clamp-2"><?php echo e($post->excerpt); ?></p>
                                        </div>
                                    </a>
                                </article>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        
                        <div class="mt-10">
                            <?php echo e($posts->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="card p-12 text-center">
                            <i data-lucide="newspaper" class="w-16 h-16 mx-auto mb-4 text-neutral-300"></i>
                            <h3 class="text-xl font-semibold text-neutral-900 mb-2">Belum Ada Berita</h3>
                            <p class="text-neutral-600">Berita dan informasi kegiatan akan segera hadir.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function searchComponent() {
    return {
        query: '',
        results: [],
        loading: false,
        showResults: false,
        
        async search() {
            if (this.query.length < 2) {
                this.results = [];
                this.showResults = false;
                return;
            }
            
            this.loading = true;
            this.showResults = true;
            
            try {
                const response = await fetch(`/api/search?q=${encodeURIComponent(this.query)}`);
                this.results = await response.json();
            } catch (error) {
                console.error('Search error:', error);
                this.results = [];
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\webpkm2026\resources\views/berita/index.blade.php ENDPATH**/ ?>