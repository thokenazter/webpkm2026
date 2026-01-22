@extends('admin.layouts.app')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')
    <div class="max-w-xl">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card p-6 space-y-6">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-neutral-700 mb-2">Nama Kategori <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}"
                        class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        required>
                </div>

                {{-- Type --}}
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Tipe Kategori <span
                            class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="berita" {{ old('type', $category->type) === 'berita' ? 'checked' : '' }} class="peer sr-only" required>
                            <div
                                class="p-4 border-2 border-neutral-200 rounded-lg peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                        <i data-lucide="newspaper" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-neutral-900">Berita</p>
                                        <p class="text-xs text-neutral-500">Untuk artikel</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="galeri" {{ old('type', $category->type) === 'galeri' ? 'checked' : '' }} class="peer sr-only">
                            <div
                                class="p-4 border-2 border-neutral-200 rounded-lg peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                                        <i data-lucide="images" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-neutral-900">Galeri</p>
                                        <p class="text-xs text-neutral-500">Untuk foto</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-neutral-700 mb-2">Deskripsi</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('description', $category->description) }}</textarea>
                </div>

                {{-- Info --}}
                <div class="p-4 bg-neutral-50 rounded-lg">
                    <p class="text-sm text-neutral-600">
                        <strong>Slug:</strong> <code class="bg-neutral-200 px-2 py-0.5 rounded">{{ $category->slug }}</code>
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pt-4 border-t border-neutral-200">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        Update
                    </button>
                    <a href="{{ route('admin.categories.index') }}"
                        class="btn border border-neutral-300 text-neutral-600 hover:bg-neutral-100">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection