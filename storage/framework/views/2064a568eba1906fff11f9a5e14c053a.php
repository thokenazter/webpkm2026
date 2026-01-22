

<?php $__env->startSection('title', 'Upload Foto'); ?>
<?php $__env->startSection('page-title', 'Upload Foto Baru'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-2xl">
        <form action="<?php echo e(route('admin.galleries.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <div class="space-y-6">
                
                <div class="card p-6">
                    <label class="block text-sm font-semibold text-neutral-900 mb-4">Foto <span
                            class="text-red-500">*</span></label>

                    <div class="border-2 border-dashed border-neutral-300 rounded-lg p-8 text-center hover:border-primary-500 transition-colors"
                        id="dropzone">
                        <input type="file" id="image" name="image" accept="image/*" class="hidden"
                            onchange="previewImage(this)" required>
                        <label for="image" class="cursor-pointer">
                            <i data-lucide="upload-cloud" class="w-12 h-12 mx-auto text-neutral-400 mb-3"></i>
                            <p class="text-neutral-600">Klik atau seret foto ke sini</p>
                            <p class="text-xs text-neutral-400 mt-1">JPG, PNG, WebP (Maks. 10MB)</p>
                        </label>
                    </div>

                    <div id="image-preview" class="hidden mt-4">
                        <img src="" alt="Preview" class="w-full max-h-64 object-contain rounded-lg bg-neutral-100">
                        <button type="button" onclick="removeImage()" class="mt-2 text-sm text-red-600 hover:text-red-700">
                            <i data-lucide="x" class="w-4 h-4 inline"></i> Hapus
                        </button>
                    </div>

                    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="card p-6">
                    <label for="title" class="block text-sm font-medium text-neutral-700 mb-2">Judul/Caption <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="<?php echo e(old('title')); ?>"
                        class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Judul atau caption foto..." required>
                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="card p-6">
                    <label for="description" class="block text-sm font-medium text-neutral-700 mb-2">Keterangan</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Keterangan singkat tentang foto ini..."><?php echo e(old('description')); ?></textarea>
                </div>

                
                <div class="card p-6 grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-neutral-700 mb-2">Kategori</label>
                        <select id="category_id" name="category_id"
                            class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Pilih Kategori</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-neutral-700 mb-2">Tanggal
                            Kegiatan</label>
                        <input type="date" id="event_date" name="event_date" value="<?php echo e(old('event_date')); ?>"
                            class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                
                <div class="card p-6">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="is_published" name="is_published" value="1" <?php echo e(old('is_published', true) ? 'checked' : ''); ?>

                            class="w-4 h-4 text-primary-600 border-neutral-300 rounded focus:ring-primary-500">
                        <label for="is_published" class="text-sm text-neutral-700">Publikasikan langsung</label>
                    </div>
                </div>

                
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="upload" class="w-5 h-5"></i>
                        Upload Foto
                    </button>
                    <a href="<?php echo e(route('admin.galleries.index')); ?>"
                        class="btn border border-neutral-300 text-neutral-600 hover:bg-neutral-100">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.querySelector('#image-preview img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                    document.getElementById('dropzone').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            document.getElementById('image').value = '';
            document.getElementById('image-preview').classList.add('hidden');
            document.getElementById('dropzone').classList.remove('hidden');
        }
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\webpkm2026\resources\views/admin/galleries/create.blade.php ENDPATH**/ ?>