

<?php $__env->startSection('title', 'Kelola Galeri'); ?>
<?php $__env->startSection('page-title', 'Kelola Galeri'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <p class="text-neutral-600">Kelola foto kegiatan Puskesmas</p>
        </div>
        <a href="<?php echo e(route('admin.galleries.create')); ?>" class="btn btn-primary">
            <i data-lucide="upload" class="w-5 h-5"></i>
            Upload Foto
        </a>
    </div>

    
    <div class="card p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            
            <select name="category"
                class="px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Kategori</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                        <?php echo e($category->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            
            <select name="status"
                class="px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Status</option>
                <option value="published" <?php echo e(request('status') === 'published' ? 'selected' : ''); ?>>Dipublikasi</option>
                <option value="draft" <?php echo e(request('status') === 'draft' ? 'selected' : ''); ?>>Draft</option>
            </select>

            <button type="submit" class="btn btn-secondary">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Filter
            </button>

            <?php if(request()->hasAny(['category', 'status'])): ?>
                <a href="<?php echo e(route('admin.galleries.index')); ?>"
                    class="btn border border-neutral-300 text-neutral-600 hover:bg-neutral-100">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    Reset
                </a>
            <?php endif; ?>
        </form>
    </div>

    
    <?php if($galleries->count() > 0): ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php $__currentLoopData = $galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card overflow-hidden group">
                    <div class="aspect-square relative">
                        <img src="<?php echo e($gallery->image_url); ?>" alt="<?php echo e($gallery->title); ?>"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">

                        
                        <div
                            class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <a href="<?php echo e(route('admin.galleries.edit', $gallery)); ?>"
                                class="p-2 bg-white rounded-lg text-neutral-700 hover:bg-primary-500 hover:text-white transition-colors">
                                <i data-lucide="edit" class="w-5 h-5"></i>
                            </a>
                            <form action="<?php echo e(route('admin.galleries.destroy', $gallery)); ?>" method="POST" class="inline"
                                onsubmit="return confirm('Yakin ingin menghapus foto ini?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit"
                                    class="p-2 bg-white rounded-lg text-neutral-700 hover:bg-red-500 hover:text-white transition-colors">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </form>
                        </div>

                        
                        <?php if(!$gallery->is_published): ?>
                            <div class="absolute top-2 left-2">
                                <span class="px-2 py-1 bg-yellow-500 text-white text-xs rounded-full">Draft</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-3">
                        <h3 class="text-sm font-medium text-neutral-900 truncate"><?php echo e($gallery->title); ?></h3>
                        <p class="text-xs text-neutral-500 mt-1">
                            <?php echo e($gallery->category->name ?? 'Tanpa Kategori'); ?>

                            <?php if($gallery->event_date): ?>
                                • <?php echo e($gallery->event_date->format('d M Y')); ?>

                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <?php if($galleries->hasPages()): ?>
            <div class="mt-8">
                <?php echo e($galleries->links()); ?>

            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="card p-12 text-center">
            <i data-lucide="image" class="w-16 h-16 mx-auto mb-4 text-neutral-300"></i>
            <p class="text-neutral-500 mb-4">Belum ada foto di galeri</p>
            <a href="<?php echo e(route('admin.galleries.create')); ?>" class="btn btn-primary">
                <i data-lucide="upload" class="w-5 h-5"></i>
                Upload Foto Pertama
            </a>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\webpkm2026\resources\views/admin/galleries/index.blade.php ENDPATH**/ ?>