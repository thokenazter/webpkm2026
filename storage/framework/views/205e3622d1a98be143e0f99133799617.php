<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin Panel'); ?> - Puskesmas</title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-neutral-100 min-h-screen" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-neutral-900 transform transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <div class="flex items-center gap-3 px-6 py-5 border-b border-neutral-800">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="w-10 h-10 object-contain">
                <div>
                    <span class="text-lg font-bold text-white">Puskesmas</span>
                    <span class="text-xs text-neutral-400 block">Admin Panel</span>
                </div>
            </div>

            
            <nav class="px-4 py-6 space-y-1 overflow-y-auto h-[calc(100vh-180px)]">
                <a href="<?php echo e(route('admin.dashboard')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                          <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-primary-600 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white'); ?>">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    Dashboard
                </a>

                <div class="pt-4">
                    <p class="px-4 text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">Konten</p>
                </div>

                <a href="<?php echo e(route('admin.posts.index')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                          <?php echo e(request()->routeIs('admin.posts.*') ? 'bg-primary-600 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white'); ?>">
                    <i data-lucide="newspaper" class="w-5 h-5"></i>
                    Berita
                </a>

                <a href="<?php echo e(route('admin.galleries.index')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                          <?php echo e(request()->routeIs('admin.galleries.*') ? 'bg-primary-600 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white'); ?>">
                    <i data-lucide="images" class="w-5 h-5"></i>
                    Galeri
                </a>

                <a href="<?php echo e(route('admin.categories.index')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                          <?php echo e(request()->routeIs('admin.categories.*') ? 'bg-primary-600 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white'); ?>">
                    <i data-lucide="tags" class="w-5 h-5"></i>
                    Kategori
                </a>

                <a href="<?php echo e(route('admin.messages.index')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors
                          <?php echo e(request()->routeIs('admin.messages.*') ? 'bg-primary-600 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white'); ?>">
                    <i data-lucide="mail" class="w-5 h-5"></i>
                    Pesan Masuk
                    <?php $unreadMessages = \App\Models\Message::unread()->count(); ?>
                    <?php if($unreadMessages > 0): ?>
                        <span
                            class="ml-auto inline-flex items-center justify-center w-5 h-5 text-xs font-bold bg-red-500 text-white rounded-full">
                            <?php echo e($unreadMessages > 9 ? '9+' : $unreadMessages); ?>

                        </span>
                    <?php endif; ?>
                </a>

                <div class="pt-4">
                    <p class="px-4 text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">Lainnya</p>
                </div>

                <a href="/" target="_blank"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-colors">
                    <i data-lucide="external-link" class="w-5 h-5"></i>
                    Lihat Website
                </a>
            </nav>

            
            <div class="absolute bottom-0 left-0 right-0 px-4 py-4 border-t border-neutral-800">
                <div class="flex items-center gap-3 px-4 py-2">
                    <div
                        class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold">
                        <?php echo e(substr(auth()->user()->name ?? 'A', 0, 1)); ?>

                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate"><?php echo e(auth()->user()->name ?? 'Admin'); ?></p>
                        <p class="text-xs text-neutral-400 truncate"><?php echo e(auth()->user()->email ?? ''); ?></p>
                    </div>
                </div>
            </div>
        </aside>

        
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <header class="bg-white border-b border-neutral-200 px-4 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-neutral-100">
                        <i data-lucide="menu" class="w-6 h-6 text-neutral-600"></i>
                    </button>

                    
                    <h1 class="text-xl font-semibold text-neutral-900 hidden lg:block"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?>
                    </h1>

                    
                    <div class="flex items-center gap-4">
                        <form action="<?php echo e(route('admin.logout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                <span class="hidden sm:inline">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            
            <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                
                <?php if(session('success')): ?>
                    <div
                        class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-3">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-cloak>
    </div>

    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH C:\laragon\www\webpkm2026\resources\views/admin/layouts/app.blade.php ENDPATH**/ ?>