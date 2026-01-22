@extends('admin.layouts.app')

@section('title', 'Edit Foto')
@section('page-title', 'Edit Foto')

@section('content')
    <div class="max-w-2xl">
        <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Current Image --}}
                <div class="card p-6">
                    <label class="block text-sm font-semibold text-neutral-900 mb-4">Foto Saat Ini</label>
                    <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" class="w-full max-h-64 object-contain rounded-lg bg-neutral-100">
                </div>

                {{-- Replace Image --}}
                <div class="card p-6">
                    <label class="block text-sm font-semibold text-neutral-900 mb-4">Ganti Foto (Opsional)</label>
                    
                    <div class="border-2 border-dashed border-neutral-300 rounded-lg p-6 text-center hover:border-primary-500 transition-colors" id="dropzone">
                        <input type="file" 
                               id="image" 
                               name="image" 
                               accept="image/*"
                               class="hidden"
                               onchange="previewImage(this)">
                        <label for="image" class="cursor-pointer">
                            <i data-lucide="upload-cloud" class="w-10 h-10 mx-auto text-neutral-400 mb-2"></i>
                            <p class="text-sm text-neutral-600">Klik untuk ganti foto</p>
                        </label>
                    </div>
                    
                    <div id="image-preview" class="hidden mt-4">
                        <img src="" alt="Preview" class="w-full max-h-48 object-contain rounded-lg bg-neutral-100">
                        <button type="button" onclick="removeImage()" class="mt-2 text-sm text-red-600 hover:text-red-700">
                            <i data-lucide="x" class="w-4 h-4 inline"></i> Batalkan
                        </button>
                    </div>
                </div>

                {{-- Title --}}
                <div class="card p-6">
                    <label for="title" class="block text-sm font-medium text-neutral-700 mb-2">Judul/Caption <span class="text-red-500">*</span></label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $gallery->title) }}"
                           class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           required>
                </div>

                {{-- Description --}}
                <div class="card p-6">
                    <label for="description" class="block text-sm font-medium text-neutral-700 mb-2">Keterangan</label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('description', $gallery->description) }}</textarea>
                </div>

                {{-- Category & Date --}}
                <div class="card p-6 grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-neutral-700 mb-2">Kategori</label>
                        <select id="category_id" 
                                name="category_id"
                                class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $gallery->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-neutral-700 mb-2">Tanggal Kegiatan</label>
                        <input type="date" 
                               id="event_date" 
                               name="event_date" 
                               value="{{ old('event_date', $gallery->event_date?->format('Y-m-d')) }}"
                               class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                {{-- Publish Status --}}
                <div class="card p-6">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" 
                               id="is_published" 
                               name="is_published" 
                               value="1"
                               {{ old('is_published', $gallery->is_published) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 border-neutral-300 rounded focus:ring-primary-500">
                        <label for="is_published" class="text-sm text-neutral-700">Dipublikasikan</label>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.galleries.index') }}" class="btn border border-neutral-300 text-neutral-600 hover:bg-neutral-100">
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
        reader.onload = function(e) {
            document.querySelector('#image-preview img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('image-preview').classList.add('hidden');
}
</script>
@endpush
