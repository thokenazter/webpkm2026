@extends('layouts.app')

@section('title', $post->title . ' - Berita Puskesmas')

@section('content')
    {{-- Breadcrumb --}}
    <div class="pt-24 lg:pt-28 bg-neutral-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center gap-2 text-sm">
                <a href="/" class="text-neutral-500 hover:text-primary-600">Beranda</a>
                <i data-lucide="chevron-right" class="w-4 h-4 text-neutral-400"></i>
                <a href="{{ route('berita.index') }}" class="text-neutral-500 hover:text-primary-600">Berita</a>
                <i data-lucide="chevron-right" class="w-4 h-4 text-neutral-400"></i>
                <span class="text-neutral-900 truncate max-w-xs">{{ $post->title }}</span>
            </nav>
        </div>
    </div>

    {{-- Article --}}
    <article class="py-8 lg:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <header class="mb-8">
                @if($post->category)
                    <span class="inline-block px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm font-medium mb-4">
                        {{ $post->category->name }}
                    </span>
                @endif

                <h1 class="text-3xl lg:text-4xl font-bold text-neutral-900 leading-tight mb-4">
                    {{ $post->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-4 text-sm text-neutral-500">
                    <span class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        {{ $post->formatted_date }}
                    </span>
                    @if($post->author)
                        <span class="flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            {{ $post->author }}
                        </span>
                    @endif
                    <span class="flex items-center gap-2">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                        {{ number_format($post->views_count) }} views
                    </span>
                </div>
            </header>

            {{-- Featured Image --}}
            @if($post->featured_image)
                <figure class="mb-8 rounded-2xl overflow-hidden">
                    <img src="{{ $post->featured_image_url }}" 
                         alt="{{ $post->title }}" 
                         class="w-full max-h-[500px] object-cover">
                </figure>
            @endif

            {{-- Content --}}
            <div class="prose prose-lg max-w-none prose-headings:text-neutral-900 prose-p:text-neutral-600 prose-a:text-primary-600 prose-img:rounded-xl">
                {!! $post->content !!}
            </div>

            {{-- Share Buttons --}}
            <div class="mt-10 pt-8 border-t border-neutral-200">
                <p class="text-sm font-medium text-neutral-900 mb-3">Bagikan artikel ini:</p>
                <div class="flex gap-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                       target="_blank"
                       class="p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" 
                       target="_blank"
                       class="p-3 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . request()->url()) }}" 
                       target="_blank"
                       class="p-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i data-lucide="message-circle" class="w-5 h-5"></i>
                    </a>
                    <button onclick="navigator.clipboard.writeText('{{ request()->url() }}'); alert('Link berhasil disalin!')"
                            class="p-3 bg-neutral-200 text-neutral-700 rounded-lg hover:bg-neutral-300 transition-colors">
                        <i data-lucide="link" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
        </div>
    </article>

    {{-- Related Posts --}}
    @if($relatedPosts->count() > 0)
        <section class="py-12 bg-neutral-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-neutral-900 mb-6">Berita Terkait</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $related)
                        <article class="card overflow-hidden group">
                            <a href="{{ route('berita.show', $related) }}" class="block">
                                <div class="aspect-video relative overflow-hidden">
                                    @if($related->featured_image)
                                        <img src="{{ $related->featured_image_url }}" 
                                             alt="{{ $related->title }}" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                                            <i data-lucide="newspaper" class="w-10 h-10 text-primary-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <time class="text-xs text-neutral-500">{{ $related->formatted_date }}</time>
                                    <h3 class="font-semibold text-neutral-900 mt-1 line-clamp-2 group-hover:text-primary-600 transition-colors">
                                        {{ $related->title }}
                                    </h3>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Back Link --}}
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('berita.index') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali ke Daftar Berita
            </a>
        </div>
    </div>
@endsection
