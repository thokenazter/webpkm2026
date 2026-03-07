<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white flex items-center justify-between">
                    <h1 class="text-2xl font-bold">Detail Kegiatan</h1>
                    <a href="{{ route('rab-kegiatans.index') }}" class="inline-flex items-center bg-white text-indigo-700 px-4 py-2 rounded-lg font-medium hover:bg-indigo-50 shadow-sm"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-6 space-y-4">
                    <div>
                        <div class="text-xs text-gray-500">Rincian Menu</div>
                        <div class="text-lg font-semibold">{{ $kegiatan->menu?->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Nama</div>
                        <div class="text-lg font-semibold">{{ $kegiatan->name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
