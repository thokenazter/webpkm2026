@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Berita --}}
        <div class="card p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i data-lucide="newspaper" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-neutral-500">Total Berita</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $stats['total_posts'] }}</p>
                </div>
            </div>
        </div>

        {{-- Published --}}
        <div class="card p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-neutral-500">Dipublikasi</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $stats['published_posts'] }}</p>
                </div>
            </div>
        </div>

        {{-- Total Galeri --}}
        <div class="card p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i data-lucide="images" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-neutral-500">Total Galeri</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $stats['total_galleries'] }}</p>
                </div>
            </div>
        </div>

        {{-- Total Views --}}
        <div class="card p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                    <i data-lucide="eye" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm text-neutral-500">Total Views</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ number_format($stats['total_views']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
        {{-- Recent Posts --}}
        <div class="card">
            <div class="p-6 border-b border-neutral-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-neutral-900">Berita Terbaru</h2>
                    <a href="{{ route('admin.posts.index') }}" class="text-sm text-primary-600 hover:text-primary-700">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="divide-y divide-neutral-100">
                @forelse($recentPosts as $post)
                    <div class="p-4 hover:bg-neutral-50 transition-colors">
                        <div class="flex items-start gap-4">
                            @if($post->featured_image)
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}"
                                    class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                            @else
                                <div class="w-16 h-16 rounded-lg bg-neutral-100 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="image" class="w-6 h-6 text-neutral-400"></i>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium text-neutral-900 truncate">{{ $post->title }}</h3>
                                <p class="text-xs text-neutral-500 mt-1">
                                    {{ $post->category->name ?? 'Tanpa Kategori' }} • {{ $post->created_at->diffForHumans() }}
                                </p>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 mt-2 rounded-full text-xs font-medium {{ $post->is_published ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $post->is_published ? 'Dipublikasi' : 'Draft' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-neutral-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-neutral-300"></i>
                        <p>Belum ada berita</p>
                        <a href="{{ route('admin.posts.create') }}"
                            class="text-sm text-primary-600 hover:text-primary-700 mt-2 inline-block">
                            Buat Berita Pertama
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Galleries --}}
        <div class="card">
            <div class="p-6 border-b border-neutral-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-neutral-900">Galeri Terbaru</h2>
                    <a href="{{ route('admin.galleries.index') }}" class="text-sm text-primary-600 hover:text-primary-700">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="p-4">
                @if($recentGalleries->count() > 0)
                    <div class="grid grid-cols-3 gap-3">
                        @foreach($recentGalleries as $gallery)
                            <div class="aspect-square rounded-lg overflow-hidden bg-neutral-100 relative group">
                                <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                <div
                                    class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <p class="text-white text-xs text-center px-2 truncate">{{ $gallery->title }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center text-neutral-500">
                        <i data-lucide="image" class="w-12 h-12 mx-auto mb-3 text-neutral-300"></i>
                        <p>Belum ada galeri</p>
                        <a href="{{ route('admin.galleries.create') }}"
                            class="text-sm text-primary-600 hover:text-primary-700 mt-2 inline-block">
                            Upload Foto Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="mt-8">
        <h2 class="text-lg font-semibold text-neutral-900 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('admin.posts.create') }}"
                class="card p-4 hover:shadow-md transition-shadow text-center group">
                <div
                    class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="plus" class="w-6 h-6"></i>
                </div>
                <p class="text-sm font-medium text-neutral-700">Buat Berita</p>
            </a>
            <a href="{{ route('admin.galleries.create') }}"
                class="card p-4 hover:shadow-md transition-shadow text-center group">
                <div
                    class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="upload" class="w-6 h-6"></i>
                </div>
                <p class="text-sm font-medium text-neutral-700">Upload Galeri</p>
            </a>
            <a href="{{ route('admin.categories.index') }}"
                class="card p-4 hover:shadow-md transition-shadow text-center group">
                <div
                    class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="tags" class="w-6 h-6"></i>
                </div>
                <p class="text-sm font-medium text-neutral-700">Kelola Kategori</p>
            </a>
            <a href="/" target="_blank" class="card p-4 hover:shadow-md transition-shadow text-center group">
                <div
                    class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <i data-lucide="external-link" class="w-6 h-6"></i>
                </div>
                <p class="text-sm font-medium text-neutral-700">Lihat Website</p>
            </a>
        </div>
    </div>
@endsection