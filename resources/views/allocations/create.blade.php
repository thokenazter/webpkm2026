<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white flex items-center justify-between">
                    <h1 class="text-2xl font-bold">Tambah Alokasi Kegiatan</h1>
                    <a href="{{ route('allocations.index') }}" class="inline-flex items-center bg-white text-indigo-700 px-4 py-2 rounded-lg font-medium hover:bg-indigo-50 shadow-sm"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-6">
                    <form method="POST" action="{{ route('allocations.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun/Pagu</label>
                                <select id="budgetSelect" name="annual_budget_id" class="mt-1 block w-full rounded-lg border-gray-300" required>
                                    <option value="">Pilih Tahun</option>
                                    @foreach ($budgets as $b)
                                        <option value="{{ $b->id }}" {{ old('annual_budget_id') == $b->id ? 'selected' : '' }}>{{ $b->year }} - {{ $b->name }}</option>
                                    @endforeach
                                </select>
                                @error('annual_budget_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">RAB/Kegiatan</label>
                                <select id="rabSelect" name="rab_id" class="mt-1 block w-full rounded-lg border-gray-300" required disabled>
                                    <option value="">Pilih Tahun terlebih dahulu</option>
                                </select>
                                @error('rab_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Alokasi</label>
                                <input id="allocatedAmount" type="number" step="0.01" name="allocated_amount" value="{{ old('allocated_amount') }}" class="mt-1 block w-full rounded-lg border-gray-300" required>
                                @error('allocated_amount')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                                <textarea name="notes" class="mt-1 block w-full rounded-lg border-gray-300" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="mt-6">
                            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const budgetSel = document.getElementById('budgetSelect');
    const rabSel = document.getElementById('rabSelect');
    const oldRab = '{{ old('rab_id') }}';
    const allocatedInput = document.getElementById('allocatedAmount');

    async function fetchRabBasic(rabId) {
        try {
            const url = `{{ url('/api/rabs') }}/${encodeURIComponent(rabId)}/basic`;
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const json = await res.json();
            const total = json?.data?.total ?? null;
            if (typeof total === 'number') {
                allocatedInput.value = String(total);
            }
        } catch (e) {
            console.warn('Fetch RAB basic failed', e);
        }
    }

    async function loadRabs(budgetId) {
        rabSel.innerHTML = '';
        rabSel.disabled = true;
        if (!budgetId) {
            rabSel.innerHTML = '<option value="">Pilih Tahun terlebih dahulu</option>';
            return;
        }
        try {
            const url = `{{ route('allocations.available_rabs') }}?budget_id=${encodeURIComponent(budgetId)}`;
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const json = await res.json();
            const data = Array.isArray(json.data) ? json.data : [];
            if (data.length === 0) {
                rabSel.innerHTML = '<option value="">Semua kegiatan sudah dialokasikan untuk tahun ini</option>';
                rabSel.disabled = true;
                return;
            }
            let options = '<option value="">Pilih RAB</option>';
            for (const item of data) {
                const sel = (oldRab && String(item.id) === String(oldRab)) ? ' selected' : '';
                options += `<option value="${item.id}"${sel}>${item.label}</option>`;
            }
            rabSel.innerHTML = options;
            rabSel.disabled = false;
            if (oldRab) {
                fetchRabBasic(oldRab);
            }
        } catch (e) {
            rabSel.innerHTML = '<option value="">Gagal memuat daftar RAB</option>';
            rabSel.disabled = true;
            console.warn('Fetch available RABs failed', e);
        }
    }

    budgetSel?.addEventListener('change', (e) => loadRabs(e.target.value));

    rabSel?.addEventListener('change', (e) => {
        const val = e.target.value;
        if (val) fetchRabBasic(val);
    });

    // Auto-load if old budget exists (after validation error)
    if (budgetSel && budgetSel.value) {
        loadRabs(budgetSel.value);
    }
});
</script>
