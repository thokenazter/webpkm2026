<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold mb-1">Edit RAB</h1>
                            <p class="text-indigo-100">Perbarui rincian anggaran kegiatan</p>
                        </div>
                        <a href="{{ route('rabs.show', $rab) }}" class="inline-flex items-center bg-white text-indigo-700 px-4 py-2 rounded-lg font-medium hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-6">
                    <form method="POST" action="{{ route('rabs.update', $rab) }}">
                        @csrf
                        @method('PUT')
                        @include('rabs._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
