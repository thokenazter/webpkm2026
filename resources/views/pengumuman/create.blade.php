<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Buat Pengumuman Baru') }}
            </h2>
            <a href="{{ route('pengumuman.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('pengumuman.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label for="judul" class="block text-sm font-medium text-gray-700">Judul Pengumuman</label>
                                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" 
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('judul') border-red-500 @enderror" 
                                    required>
                                @error('judul')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="isi" class="block text-sm font-medium text-gray-700">Isi Pengumuman</label>
                                <textarea name="isi" id="isi" rows="5" 
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('isi') border-red-500 @enderror" 
                                    required>{{ old('isi') }}</textarea>
                                @error('isi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="prioritas" class="block text-sm font-medium text-gray-700">Prioritas</label>
                                <select name="prioritas" id="prioritas" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('prioritas') border-red-500 @enderror">
                                    <option value="low" {{ old('prioritas') == 'low' ? 'selected' : '' }}>Rendah</option>
                                    <option value="medium" {{ old('prioritas', 'medium') == 'medium' ? 'selected' : '' }}>Sedang</option>
                                    <option value="high" {{ old('prioritas') == 'high' ? 'selected' : '' }}>Tinggi</option>
                                </select>
                                @error('prioritas')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="warna_tema" class="block text-sm font-medium text-gray-700">Warna Tema</label>
                                <div class="mt-1 flex items-center space-x-2">
                                    <input type="color" name="warna_tema" id="warna_tema" value="{{ old('warna_tema', '#3B82F6') }}" 
                                        class="h-10 w-20 border border-gray-300 rounded-md cursor-pointer @error('warna_tema') border-red-500 @enderror">
                                    <span class="text-sm text-gray-500">Pilih warna untuk tema popup</span>
                                </div>
                                @error('warna_tema')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai (Opsional)</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}" 
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('tanggal_mulai') border-red-500 @enderror">
                                @error('tanggal_mulai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">Tanggal Selesai (Opsional)</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}" 
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('tanggal_selesai') border-red-500 @enderror">
                                @error('tanggal_selesai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                        Aktifkan pengumuman ini
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('pengumuman.index') }}" 
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Pengumuman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>