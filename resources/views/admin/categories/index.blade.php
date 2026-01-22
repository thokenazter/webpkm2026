@extends('admin.layouts.app')

@section('title', 'Kelola Kategori')
@section('page-title', 'Kelola Kategori')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <p class="text-neutral-600">Kelola kategori untuk berita dan galeri</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Tambah Kategori
        </a>
    </div>

    {{-- Categories Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 border-b border-neutral-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider hidden sm:table-cell">Slug</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Digunakan</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-neutral-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($categories as $category)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-neutral-900">{{ $category->name }}</p>
                                @if($category->description)
                                    <p class="text-xs text-neutral-500 mt-1 truncate max-w-xs">{{ $category->description }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($category->type === 'berita')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        <i data-lucide="newspaper" class="w-3 h-3 mr-1"></i>
                                        Berita
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                        <i data-lucide="images" class="w-3 h-3 mr-1"></i>
                                        Galeri
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 hidden sm:table-cell">
                                <code class="text-xs text-neutral-600 bg-neutral-100 px-2 py-1 rounded">{{ $category->slug }}</code>
                            </td>
                            <td class="px-6 py-4">
                                @if($category->type === 'berita')
                                    <span class="text-sm text-neutral-600">{{ $category->posts_count }} berita</span>
                                @else
                                    <span class="text-sm text-neutral-600">{{ $category->galleries_count }} foto</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="p-2 text-neutral-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors"
                                       title="Edit">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    @if($category->posts_count == 0 && $category->galleries_count == 0)
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-neutral-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="p-2 text-neutral-300 cursor-not-allowed" title="Tidak dapat dihapus (masih digunakan)">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-neutral-500">
                                    <i data-lucide="tags" class="w-12 h-12 mx-auto mb-3 text-neutral-300"></i>
                                    <p class="mb-2">Belum ada kategori</p>
                                    <a href="{{ route('admin.categories.create') }}" class="text-primary-600 hover:text-primary-700">
                                        Tambah kategori pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
