<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Tarif Per Diem') }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('per-diem-rates.edit', $perDiemRate) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('per-diem-rates.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pangkat/Golongan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $perDiemRate->pangkat_golongan }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tarif Per Hari</label>
                            <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($perDiemRate->rate_per_day, 0, ',', '.') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Berlaku Dari</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $perDiemRate->valid_from->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Berlaku Sampai</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $perDiemRate->valid_to ? $perDiemRate->valid_to->format('d/m/Y') : 'Tidak terbatas' }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @php
                                    $isActive = $perDiemRate->valid_from <= now() && ($perDiemRate->valid_to === null || $perDiemRate->valid_to >= now());
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $isActive ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dibuat</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $perDiemRate->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Diperbarui</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $perDiemRate->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>