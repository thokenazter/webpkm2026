<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-start md:items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold mb-1">Master RAB - Rincian Menu</h1>
                            <p class="text-indigo-100">Kelola daftar Rincian Menu per Komponen</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="{{ route('rab-menus.create') }}" class="inline-flex items-center bg-white text-indigo-700 px-4 py-2 rounded-lg font-medium hover:bg-indigo-50 shadow-sm"><i class="fas fa-plus mr-2"></i>Tambah</a>
                        </div>
                    </div>
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
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Komponen</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($menus as $menu)
                                    <tr>
                                        @php
                                            $compName = \App\Models\Rab::components()[$menu->component_key] ?? $menu->component_key;
                                            $componentBadges = [
                                                'Peningkatan Layanan Kesehatan Sesuai Siklus Hidup' => 'bg-pink-100 text-pink-800 border-pink-200',
                                                'Surveilans, respons penyakit dan kesehatan lingkungan' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                                'Pemberian Makanan Tambahan (PMT) berbahan pangan lokal' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                'MANAGEMEN PUSKESMAS' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                                'INSENTIF UKM' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            ];
                                            $badgeClass = $componentBadges[$compName] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                        @endphp
                                        <td class="px-6 py-3 text-sm">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                                {{ $compName }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 text-sm">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                                {{ $menu->name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 text-sm">
                                            <a href="{{ route('rab-menus.show', $menu) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-eye mr-1"></i>Lihat</a>
                                            <a href="{{ route('rab-menus.edit', $menu) }}" class="inline-flex items-center text-yellow-600 hover:text-yellow-900 mr-3"><i class="fas fa-edit mr-1"></i>Edit</a>
                                            <form action="{{ route('rab-menus.destroy', $menu) }}" method="POST" class="inline" onsubmit="return confirm('Yakin?')">
                                                @csrf @method('DELETE')
                                                <button class="inline-flex items-center text-red-600 hover:text-red-900"><i class="fas fa-trash mr-1"></i>Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $menus->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
