<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pengaturan Tarif') }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('rate-settings.edit', $rateSetting) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('rate-settings.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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
                            <label class="block text-sm font-medium text-gray-700">Key</label>
                            <p class="mt-1 text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $rateSetting->key }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Tarif</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $rateSetting->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nilai</label>
                            <p class="mt-1 text-lg font-bold text-green-600">
                                Rp {{ number_format($rateSetting->value, 0, ',', '.') }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $rateSetting->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $rateSetting->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $rateSetting->description ?: 'Tidak ada deskripsi' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dibuat</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $rateSetting->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Diperbarui</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $rateSetting->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($rateSetting->key === 'transport_rate')
                        <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                            <h3 class="text-lg font-medium text-blue-900 mb-2">Penggunaan Tarif Transport</h3>
                            <p class="text-sm text-blue-700">
                                Tarif ini digunakan untuk menghitung biaya transport dalam LPJ:
                            </p>
                            <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                                <li><strong>SPPT</strong>: Rp {{ number_format($rateSetting->value, 0, ',', '.') }} × jumlah desa darat × jumlah peserta</li>
                                <li><strong>SPPD</strong>: Rp {{ number_format($rateSetting->value, 0, ',', '.') }} × jumlah desa seberang × jumlah peserta</li>
                            </ul>
                        </div>
                    @elseif($rateSetting->key === 'per_diem_rate')
                        <div class="mt-8 p-4 bg-green-50 rounded-lg">
                            <h3 class="text-lg font-medium text-green-900 mb-2">Penggunaan Tarif Uang Harian</h3>
                            <p class="text-sm text-green-700">
                                Tarif ini digunakan untuk menghitung uang harian dalam LPJ:
                            </p>
                            <ul class="mt-2 text-sm text-green-700 list-disc list-inside">
                                <li><strong>SPPT</strong>: Tidak ada uang harian (Rp 0)</li>
                                <li><strong>SPPD</strong>: Rp {{ number_format($rateSetting->value, 0, ',', '.') }} × jumlah desa seberang (dibagi rata untuk semua peserta)</li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>