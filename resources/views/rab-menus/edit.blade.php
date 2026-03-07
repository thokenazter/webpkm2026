<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white flex items-center justify-between">
                    <h1 class="text-2xl font-bold">Edit Rincian Menu</h1>
                    <a href="{{ route('rab-menus.index') }}" class="inline-flex items-center bg-white text-indigo-700 px-4 py-2 rounded-lg font-medium hover:bg-indigo-50 shadow-sm"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-6">
                    <form method="POST" action="{{ route('rab-menus.update', $menu) }}">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Komponen</label>
                                <select name="component_key" class="mt-1 block w-full rounded-lg border-gray-300" required>
                                    <option value="">Pilih Komponen</option>
                                    @foreach ($components as $key => $label)
                                        <option value="{{ $key }}" {{ old('component_key', $menu->component_key) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('component_key')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Rincian Menu</label>
                                <input type="text" name="name" value="{{ old('name', $menu->name) }}" class="mt-1 block w-full rounded-lg border-gray-300" required>
                                @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="mt-6">
                            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
