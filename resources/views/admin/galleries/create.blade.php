@extends('admin.layouts.app')

@section('title', 'Upload Foto')
@section('page-title', 'Upload Foto Baru')

@section('content')
    <div class="max-w-2xl">
        <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                {{-- Image Upload --}}
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

                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Title --}}
                <div class="card p-6">
                    <label for="title" class="block text-sm font-medium text-neutral-700 mb-2">Judul/Caption <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                        class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Judul atau caption foto..." required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="card p-6">
                    <label for="description" class="block text-sm font-medium text-neutral-700 mb-2">Keterangan</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Keterangan singkat tentang foto ini...">{{ old('description') }}</textarea>
                </div>

                {{-- Category & Date --}}
                <div class="card p-6 grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-neutral-700 mb-2">Kategori</label>
                        <select id="category_id" name="category_id"
                            class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-neutral-700 mb-2">Tanggal
                            Kegiatan</label>
                        <input type="date" id="event_date" name="event_date" value="{{ old('event_date') }}"
                            class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                {{-- Publish Status --}}
                <div class="card p-6">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                            class="w-4 h-4 text-primary-600 border-neutral-300 rounded focus:ring-primary-500">
                        <label for="is_published" class="text-sm text-neutral-700">Publikasikan langsung</label>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="upload" class="w-5 h-5"></i>
                        Upload Foto
                    </button>
                    <a href="{{ route('admin.galleries.index') }}"
                        class="btn border border-neutral-300 text-neutral-600 hover:bg-neutral-100">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
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
@endpush