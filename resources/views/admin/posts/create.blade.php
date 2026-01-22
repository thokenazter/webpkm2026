@extends('admin.layouts.app')

@section('title', 'Buat Berita')
@section('page-title', 'Buat Berita Baru')

@section('content')
    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Title --}}
                <div class="card p-6">
                    <label for="title" class="block text-sm font-medium text-neutral-700 mb-2">Judul Berita <span class="text-red-500">*</span></label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}"
                           class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('title') border-red-500 @enderror"
                           placeholder="Masukkan judul berita..."
                           required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Content --}}
                <div class="card p-6">
                    <label for="content" class="block text-sm font-medium text-neutral-700 mb-2">Konten <span class="text-red-500">*</span></label>
                    <textarea id="content" 
                              name="content" 
                              rows="15"
                              class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('content') border-red-500 @enderror"
                              placeholder="Tulis konten berita di sini...">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-neutral-500">Mendukung format HTML dasar untuk styling.</p>
                </div>

                {{-- Excerpt --}}
                <div class="card p-6">
                    <label for="excerpt" class="block text-sm font-medium text-neutral-700 mb-2">Ringkasan</label>
                    <textarea id="excerpt" 
                              name="excerpt" 
                              rows="3"
                              class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                              placeholder="Ringkasan singkat untuk preview (opsional)...">{{ old('excerpt') }}</textarea>
                    <p class="mt-2 text-xs text-neutral-500">Jika kosong, ringkasan akan diambil dari awal konten.</p>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Publish Card --}}
                <div class="card p-6">
                    <h3 class="text-sm font-semibold text-neutral-900 mb-4">Publikasi</h3>
                    
                    <div class="space-y-4">
                        {{-- Status --}}
                        <div class="flex items-center gap-3">
                            <input type="checkbox" 
                                   id="is_published" 
                                   name="is_published" 
                                   value="1"
                                   {{ old('is_published') ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary-600 border-neutral-300 rounded focus:ring-primary-500">
                            <label for="is_published" class="text-sm text-neutral-700">Publikasikan langsung</label>
                        </div>

                        {{-- Author --}}
                        <div>
                            <label for="author" class="block text-sm font-medium text-neutral-700 mb-2">Penulis</label>
                            <input type="text" 
                                   id="author" 
                                   name="author" 
                                   value="{{ old('author', auth()->user()->name) }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-neutral-200">
                        <button type="submit" class="flex-1 btn btn-primary">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            Simpan
                        </button>
                        <a href="{{ route('admin.posts.index') }}" class="btn border border-neutral-300 text-neutral-600 hover:bg-neutral-100">
                            Batal
                        </a>
                    </div>
                </div>

                {{-- Category --}}
                <div class="card p-6">
                    <label for="category_id" class="block text-sm font-semibold text-neutral-900 mb-4">Kategori</label>
                    <select id="category_id" 
                            name="category_id"
                            class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Featured Image --}}
                <div class="card p-6">
                    <label class="block text-sm font-semibold text-neutral-900 mb-4">Gambar Utama</label>
                    
                    <div class="space-y-4">
                        <div class="border-2 border-dashed border-neutral-300 rounded-lg p-6 text-center hover:border-primary-500 transition-colors" id="dropzone">
                            <input type="file" 
                                   id="featured_image" 
                                   name="featured_image" 
                                   accept="image/*"
                                   class="hidden"
                                   onchange="previewImage(this)">
                            <label for="featured_image" class="cursor-pointer">
                                <i data-lucide="upload-cloud" class="w-10 h-10 mx-auto text-neutral-400 mb-3"></i>
                                <p class="text-sm text-neutral-600">Klik atau seret gambar ke sini</p>
                                <p class="text-xs text-neutral-400 mt-1">JPG, PNG, WebP (Maks. 10MB)</p>
                            </label>
                        </div>
                        
                        <div id="image-preview" class="hidden">
                            <img src="" alt="Preview" class="w-full h-40 object-cover rounded-lg">
                            <button type="button" onclick="removeImage()" class="mt-2 text-sm text-red-600 hover:text-red-700">
                                <i data-lucide="x" class="w-4 h-4 inline"></i> Hapus gambar
                            </button>
                        </div>
                    </div>
                    @error('featured_image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
let contentEditor;

ClassicEditor
    .create(document.querySelector('#content'), {
        toolbar: {
            items: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'link', 'blockQuote', 'insertTable', '|',
                'undo', 'redo'
            ]
        },
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
            ]
        },
        placeholder: 'Tulis konten berita di sini...',
        language: 'id'
    })
    .then(editor => {
        contentEditor = editor;
        
        // Sync CKEditor content to textarea on form submit
        editor.model.document.on('change:data', () => {
            document.querySelector('#content').value = editor.getData();
        });
    })
    .catch(error => {
        console.error(error);
    });

// Also sync before form submit
document.querySelector('form').addEventListener('submit', function(e) {
    if (contentEditor) {
        document.querySelector('#content').value = contentEditor.getData();
        
        // Validate content is not empty
        if (!contentEditor.getData().trim()) {
            e.preventDefault();
            alert('Konten berita tidak boleh kosong!');
            return false;
        }
    }
});

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('#image-preview img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
            document.getElementById('dropzone').classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    document.getElementById('featured_image').value = '';
    document.getElementById('image-preview').classList.add('hidden');
    document.getElementById('dropzone').classList.remove('hidden');
}
</script>
<style>
.ck-editor__editable {
    min-height: 350px;
    max-height: 500px;
}
.ck.ck-editor__main>.ck-editor__editable {
    background: #fff;
}
</style>
@endpush
