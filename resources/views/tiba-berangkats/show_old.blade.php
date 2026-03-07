<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Tiba Berangkat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Surat</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $tibaBerangkat->no_surat }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Desa Kunjungan</label>
                            <div class="mt-2 space-y-4">
                                @foreach ($tibaBerangkat->details as $index => $detail)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <h4 class="text-lg font-medium text-gray-900 mb-3">Desa {{ $index + 1 }}</h4>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Nama Desa</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $detail->pejabatTtd->desa }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Nama Pejabat</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $detail->pejabatTtd->nama }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $detail->pejabatTtd->jabatan }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Tanggal Kunjungan</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $detail->tanggal_kunjungan->format('d F Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dibuat</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $tibaBerangkat->created_at->format('d F Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Diperbarui</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $tibaBerangkat->updated_at->format('d F Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6 space-x-3">
                        <a href="{{ route('tiba-berangkats.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                        <a href="{{ route('tiba-berangkats.download', $tibaBerangkat) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Download Document
                        </a>
                        <a href="{{ route('tiba-berangkats.edit', $tibaBerangkat) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>