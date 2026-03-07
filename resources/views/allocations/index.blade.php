<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white flex items-center justify-between">
                    <h1 class="text-2xl font-bold">Alokasi Kegiatan</h1>
                    @if(auth()->user()?->isSuperAdmin())
                        <a href="{{ route('allocations.create') }}" class="inline-flex items-center bg-white text-indigo-700 px-4 py-2 rounded-lg font-medium hover:bg-indigo-50 shadow-sm"><i class="fas fa-plus mr-2"></i>Tambah</a>
                    @endif
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-6">
                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">{{ session('success') }}</div>
                    @endif
                    <div class="mb-4">
                        <input id="allocationsSearch" type="text" placeholder="Ketik nama kegiatan..." class="w-full md:w-96 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tahun</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kegiatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Alokasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="allocationsRows" class="bg-white divide-y divide-gray-200">
                                @include('allocations._rows', ['allocations' => $allocations])
                            </tbody>
                        </table>
                    </div>
                    <div id="allocationsPagination" class="mt-4">{{ $allocations->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const search = document.getElementById('allocationsSearch');
    const rows = document.getElementById('allocationsRows');
    const pager = document.getElementById('allocationsPagination');
    let t = null;
    async function run(q) {
        const url = new URL(window.location.href);
        url.searchParams.set('ajax', '1');
        url.searchParams.set('q', q || '');
        try {
            const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await res.text();
            rows.innerHTML = html;
            pager.style.display = q ? 'none' : '';
        } catch (e) { console.warn('Search failed', e); }
    }
    function debounce(fn, ms){ return function(...a){ clearTimeout(t); t = setTimeout(() => fn.apply(this, a), ms); } }
    search?.addEventListener('input', debounce((e) => run(e.target.value), 250));
});
</script>
