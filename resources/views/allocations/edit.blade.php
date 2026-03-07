<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white flex items-center justify-between">
                    <h1 class="text-2xl font-bold">Edit Alokasi Kegiatan</h1>
                    <a href="{{ route('allocations.index') }}" class="inline-flex items-center bg-white text-indigo-700 px-4 py-2 rounded-lg font-medium hover:bg-indigo-50 shadow-sm"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-6">
                    <form method="POST" action="{{ route('allocations.update', $allocation) }}">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun/Pagu</label>
                                <select name="annual_budget_id" class="mt-1 block w-full rounded-lg border-gray-300" required>
                                    <option value="">Pilih Tahun</option>
                                    @foreach ($budgets as $b)
                                        <option value="{{ $b->id }}" {{ old('annual_budget_id', $allocation->annual_budget_id) == $b->id ? 'selected' : '' }}>{{ $b->year }} - {{ $b->name }}</option>
                                    @endforeach
                                </select>
                                @error('annual_budget_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">RAB/Kegiatan</label>
                                <select id="rabEditSelect" name="rab_id" class="mt-1 block w-full rounded-lg border-gray-300" required>
                                    <option value="">Pilih RAB</option>
                                    @foreach ($rabs as $r)
                                        <option value="{{ $r->id }}" {{ old('rab_id', $allocation->rab_id) == $r->id ? 'selected' : '' }}>{{ $r->kegiatan }} ({{ $r->rincian_menu }} • {{ $r->komponen }})</option>
                                    @endforeach
                                </select>
                                @error('rab_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Alokasi</label>
                                <input id="allocatedAmountEdit" type="number" step="0.01" name="allocated_amount" value="{{ old('allocated_amount', $allocation->allocated_amount) }}" class="mt-1 block w-full rounded-lg border-gray-300" required>
                                @error('allocated_amount')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                                <textarea name="notes" class="mt-1 block w-full rounded-lg border-gray-300" rows="3">{{ old('notes', $allocation->notes) }}</textarea>
                                @error('notes')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="mt-6">
                            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const rabSel = document.getElementById('rabEditSelect');
    const alloc = document.getElementById('allocatedAmountEdit');
    async function fetchRabBasic(rabId) {
        try {
            const url = `{{ url('/api/rabs') }}/${encodeURIComponent(rabId)}/basic`;
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const json = await res.json();
            const total = json?.data?.total ?? null;
            if (typeof total === 'number' && (!alloc.value || alloc.value === '0')) {
                alloc.value = String(total);
            }
        } catch (e) { console.warn('Fetch RAB basic failed', e); }
    }
    rabSel?.addEventListener('change', (e) => { if (e.target.value) fetchRabBasic(e.target.value); });
});
</script>
