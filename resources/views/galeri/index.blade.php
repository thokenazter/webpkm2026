@extends('layouts.app')

@section('title', 'Galeri Kegiatan - Puskesmas')

@section('content')
    {{-- Hero Header --}}
    <section class="pt-24 lg:pt-32 pb-12 bg-gradient-to-br from-primary-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl lg:text-4xl font-bold text-neutral-900 mb-4">Galeri Kegiatan</h1>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">
                Dokumentasi foto kegiatan Puskesmas Rawat Inap Kabalsiang Benjuring
            </p>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Category Filter --}}
            <div class="mb-8 overflow-x-auto">
                <div class="flex gap-2 pb-2 justify-center">
                    <a href="{{ route('galeri.index') }}"
                        class="whitespace-nowrap px-5 py-2.5 rounded-full text-sm font-medium transition-colors {{ !request('kategori') ? 'bg-primary-600 text-white' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200' }}">
                        Semua Foto
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('galeri.index', ['kategori' => $category->slug]) }}"
                            class="whitespace-nowrap px-5 py-2.5 rounded-full text-sm font-medium transition-colors {{ request('kategori') === $category->slug ? 'bg-primary-600 text-white' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200' }}">
                            {{ $category->name }} ({{ $category->galleries_count }})
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Gallery Grid --}}
            @if($galleries->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4" x-data="{ lightbox: null }">
                    @foreach($galleries as $index => $gallery)
                        <div class="group cursor-pointer" @click="lightbox = {{ $index }}">
                            <div class="aspect-square rounded-xl overflow-hidden relative">
                                <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                    <div>
                                        <p class="text-white font-medium text-sm line-clamp-2">{{ $gallery->title }}</p>
                                        @if($gallery->event_date)
                                            <p class="text-white/80 text-xs mt-1">{{ $gallery->formatted_date }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Lightbox --}}
                    <div x-show="lightbox !== null" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
                        @keydown.escape.window="lightbox = null" x-cloak>

                        {{-- Close Button --}}
                        <button @click="lightbox = null" class="absolute top-4 right-4 p-2 text-white/80 hover:text-white">
                            <i data-lucide="x" class="w-8 h-8"></i>
                        </button>

                        {{-- Navigation --}}
                        <button @click="lightbox = (lightbox - 1 + {{ $galleries->count() }}) % {{ $galleries->count() }}"
                            class="absolute left-4 p-2 text-white/80 hover:text-white">
                            <i data-lucide="chevron-left" class="w-10 h-10"></i>
                        </button>
                        <button @click="lightbox = (lightbox + 1) % {{ $galleries->count() }}"
                            class="absolute right-4 p-2 text-white/80 hover:text-white">
                            <i data-lucide="chevron-right" class="w-10 h-10"></i>
                        </button>

                        {{-- Image Container --}}
                        <div class="max-w-5xl max-h-[80vh] w-full">
                            @foreach($galleries as $index => $gallery)
                                <div x-show="lightbox === {{ $index }}" x-transition class="text-center">
                                    <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}"
                                        class="max-w-full max-h-[70vh] mx-auto rounded-lg object-contain">
                                    <div class="mt-4 text-white">
                                        <p class="text-lg font-medium">{{ $gallery->title }}</p>
                                        @if($gallery->description)
                                            <p class="text-white/70 mt-1">{{ $gallery->description }}</p>
                                        @endif
                                        @if($gallery->event_date)
                                            <p class="text-white/50 text-sm mt-2">{{ $gallery->formatted_date }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="mt-10">
                    {{ $galleries->links() }}
                </div>
            @else
                <div class="card p-12 text-center">
                    <i data-lucide="image" class="w-16 h-16 mx-auto mb-4 text-neutral-300"></i>
                    <h3 class="text-xl font-semibold text-neutral-900 mb-2">Belum Ada Foto</h3>
                    <p class="text-neutral-600">Galeri foto kegiatan akan segera hadir.</p>
                </div>
            @endif
        </div>
    </section>
@endsection