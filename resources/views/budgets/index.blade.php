<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white flex items-center justify-between">
                    <h1 class="text-2xl font-bold">Pagu Tahunan</h1>
                    <a href="{{ route('budgets.create') }}" class="inline-flex items-center bg-white text-indigo-700 px-4 py-2 rounded-lg font-medium hover:bg-indigo-50 shadow-sm"><i class="fas fa-plus mr-2"></i>Tambah</a>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-6">
                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">{{ session('success') }}</div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tahun</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pagu</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($budgets as $b)
                                    <tr>
                                        <td class="px-6 py-3 text-sm text-gray-900">{{ $b->year }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-900">{{ $b->name }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-900">Rp {{ number_format($b->amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-3 text-sm">
                                            <a href="{{ route('budgets.show', $b) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-eye mr-1"></i>Lihat</a>
                                            <a href="{{ route('budgets.edit', $b) }}" class="inline-flex items-center text-yellow-600 hover:text-yellow-900 mr-3"><i class="fas fa-edit mr-1"></i>Edit</a>
                                            <form action="{{ route('budgets.destroy', $b) }}" method="POST" class="inline" onsubmit="return confirm('Yakin?')">
                                                @csrf @method('DELETE')
                                                <button class="inline-flex items-center text-red-600 hover:text-red-900"><i class="fas fa-trash mr-1"></i>Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $budgets->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
