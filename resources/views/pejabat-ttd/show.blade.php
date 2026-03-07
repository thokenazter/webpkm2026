<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pejabat TTD') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $pejabatTtd->nama }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Desa</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $pejabatTtd->desa }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $pejabatTtd->jabatan }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dibuat</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $pejabatTtd->created_at->format('d F Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Diperbarui</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $pejabatTtd->updated_at->format('d F Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6 space-x-3">
                        <a href="{{ route('pejabat-ttd.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('pejabat-ttd.edit', $pejabatTtd) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>