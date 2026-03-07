<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Tarif Per Diem') }}
            </h2>
            <a href="{{ route('per-diem-rates.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('per-diem-rates.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="pangkat_golongan" class="block text-sm font-medium text-gray-700">Pangkat/Golongan</label>
                                <select name="pangkat_golongan" id="pangkat_golongan" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('pangkat_golongan') border-red-500 @enderror">
                                    <option value="">Pilih Pangkat/Golongan</option>
                                    @foreach(['I/a', 'I/b', 'I/c', 'I/d', 'II/a', 'II/b', 'II/c', 'II/d', 'III/a', 'III/b', 'III/c', 'III/d', 'IV/a', 'IV/b', 'IV/c', 'IV/d', 'IV/e'] as $pangkat)
                                        <option value="{{ $pangkat }}" {{ old('pangkat_golongan') == $pangkat ? 'selected' : '' }}>{{ $pangkat }}</option>
                                    @endforeach
                                </select>
                                @error('pangkat_golongan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="rate_per_day" class="block text-sm font-medium text-gray-700">Tarif Per Hari (Rp)</label>
                                <input type="number" name="rate_per_day" id="rate_per_day" value="{{ old('rate_per_day') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('rate_per_day') border-red-500 @enderror">
                                @error('rate_per_day')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="valid_from" class="block text-sm font-medium text-gray-700">Berlaku Dari</label>
                                <input type="date" name="valid_from" id="valid_from" value="{{ old('valid_from') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('valid_from') border-red-500 @enderror">
                                @error('valid_from')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="valid_to" class="block text-sm font-medium text-gray-700">Berlaku Sampai (Opsional)</label>
                                <input type="date" name="valid_to" id="valid_to" value="{{ old('valid_to') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('valid_to') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ada batas waktu</p>
                                @error('valid_to')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>