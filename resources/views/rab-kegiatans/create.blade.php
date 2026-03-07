<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white flex items-center justify-between">
                    <h1 class="text-2xl font-bold">Tambah Kegiatan</h1>
                    <a href="{{ route('rab-kegiatans.index') }}" class="inline-flex items-center bg-white text-indigo-700 px-4 py-2 rounded-lg font-medium hover:bg-indigo-50 shadow-sm"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">{{ session('success') }}</div>
                    @endif
                    @isset($selectedMenu)
                        <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-2 rounded-xl mb-4">
                            Menambahkan Kegiatan untuk Rincian Menu: <strong>{{ $selectedMenu->name }}</strong>
                        </div>
                    @endisset
                    <form method="POST" action="{{ route('rab-kegiatans.store') }}" x-data="{ rows: [ { name: '' } ] }">
                        @csrf
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Rincian Menu</label>
                                <select name="rab_menu_id" class="mt-1 block w-full rounded-lg border-gray-300" required>
                                    <option value="">Pilih Rincian Menu</option>
                                    @foreach ($menus as $menu)
                                        <option value="{{ $menu->id }}" 
                                            {{ (old('rab_menu_id') == $menu->id) || (isset($selectedMenuId) && $selectedMenuId == $menu->id && !old('rab_menu_id')) ? 'selected' : '' }}>
                                            {{ $menu->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rab_menu_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kegiatan</label>
                                <template x-for="(row, idx) in rows" :key="idx">
                                    <div class="flex gap-2 mb-2">
                                        <input :name="`names[${idx}]`" x-model="row.name" type="text" class="flex-1 rounded-lg border-gray-300" placeholder="Nama kegiatan" required>
                                        <button type="button" class="px-3 py-2 bg-gray-100 rounded-lg" @click="rows.splice(idx,1)" x-show="rows.length > 1">Hapus</button>
                                    </div>
                                </template>
                                <div>
                                    <button type="button" class="text-sm text-indigo-700 hover:underline" @click="rows.push({ name: '' })">+ Tambah Kegiatan</button>
                                </div>
                                @error('names')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                @error('names.*')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="mt-6 flex gap-2">
                            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg">Simpan</button>
                            <button name="add_more" value="1" class="bg-white text-indigo-700 hover:bg-indigo-50 px-5 py-2 rounded-lg border border-indigo-200">Simpan & Tambah Lagi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
