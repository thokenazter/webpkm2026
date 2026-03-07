@php
    $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
    $initialSchedule = old('schedule');
    if (!$initialSchedule && isset($poa)) {
        $initialSchedule = $poa->schedule ?: [];
    }
    $initialParticipants = old('participants');
    if (!$initialParticipants && isset($poa)) {
        $initialParticipants = $poa->participants->map(function($p){
            return [
                'employee_id' => $p->employee_id,
                'role' => $p->role,
                'borrowed_employee_id' => $p->borrowed_employee_id,
                'note' => $p->note,
            ];
        })->values()->all();
    }
    // Prefill semua pegawai bila belum ada data peserta (permintaan: prefill peserta semua pegawai)
    if (!$initialParticipants && isset($employees)) {
        $initialParticipants = collect($employees)->map(function($e){
            return [
                'employee_id' => $e->id,
                'role' => '',
                'borrowed_employee_id' => '',
                'note' => '',
            ];
        })->values()->all();
    }
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
        <select name="year" id="poa_year" class="mt-1 block w-full rounded-xl border-gray-300" required>
            @foreach ($years as $y)
                <option value="{{ $y }}" {{ (int) old('year', $poa->year ?? date('Y')) === (int) $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        @error('year')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Pagu Tahunan</label>
        <select name="annual_budget_id" class="mt-1 block w-full rounded-xl border-gray-300">
            <option value="">(Opsional) Pilih Pagu</option>
            @foreach ($budgets as $b)
                <option value="{{ $b->id }}" {{ (old('annual_budget_id', $poa->annual_budget_id ?? null) == $b->id) ? 'selected' : '' }}>{{ $b->year }} - {{ $b->name }}</option>
            @endforeach
        </select>
        @error('annual_budget_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Surat</label>
        <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $poa->nomor_surat ?? '') }}" class="mt-1 block w-full rounded-xl border-gray-300">
        @error('nomor_surat')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 mb-2">RAB / Kegiatan</label>
        <select name="rab_id" id="rab_id" class="mt-1 block w-full rounded-xl border-gray-300" required>
            <option value="">Pilih RAB</option>
            @foreach ($rabs as $r)
                <option value="{{ $r->id }}" {{ (old('rab_id', $poa->rab_id ?? null) == $r->id) ? 'selected' : '' }}>
                    #{{ $r->id }} - {{ $r->kegiatan }} ({{ $r->rincian_menu }} • {{ $r->komponen }})
                </option>
            @endforeach
        </select>
        @error('rab_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        <input type="hidden" name="kegiatan" id="kegiatan_field" value="{{ old('kegiatan', $poa->kegiatan ?? '') }}">
    </div>
    <div class="md:col-span-1">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Planned Total (Rp)</label>
        <input type="number" step="0.01" name="planned_total" id="planned_total" value="{{ old('planned_total', $poa->planned_total ?? 0) }}" class="mt-1 block w-full rounded-xl border-gray-300" required>
        @error('planned_total')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-3">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Output/Target</label>
        <textarea name="output_target" rows="3" class="mt-1 block w-full rounded-xl border-gray-300">{{ old('output_target', $poa->output_target ?? '') }}</textarea>
        @error('output_target')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<!-- Jadwal (Bulan Pelaksanaan) -->
<div class="mt-8">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-bold text-gray-900">Jadwal Pelaksanaan</h3>
        <div class="flex gap-2 text-sm">
            <button type="button" id="autoDistributeBtn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-lg">Bagi Otomatis</button>
            <button type="button" id="clearScheduleBtn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-lg">Bersihkan</button>
        </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3" id="scheduleGrid">
        @foreach ($months as $m => $label)
            <div class="border rounded-xl p-3">
                <div class="text-sm font-medium text-gray-700 mb-1">{{ $label }}</div>
                <label class="text-xs text-gray-600">Jumlah Kegiatan</label>
                <input type="number" min="0" name="schedule[months][{{ $m }}][count]" value="{{ $initialSchedule['months'][$m]['count'] ?? 0 }}" class="mt-1 block w-full rounded-lg border-gray-300" />
                <label class="text-xs text-gray-600 mt-2">Anggaran Bulan (Rp)</label>
                <input type="number" step="0.01" min="0" name="schedule[months][{{ $m }}][amount]" value="{{ $initialSchedule['months'][$m]['amount'] ?? 0 }}" class="mt-1 block w-full rounded-lg border-gray-300" />
            </div>
        @endforeach
    </div>
    <input type="hidden" name="schedule[total_occurrences]" id="total_occurrences" value="{{ $initialSchedule['total_occurrences'] ?? 0 }}">
</div>

<!-- Peserta -->
<div class="mt-8" x-data="poaParticipants({{ (int) ($participantLimit ?? 0) }})" x-init="init()" id="poa-participants-panel">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-bold text-gray-900">Peserta/Tim Pelaksana</h3>
        <div class="flex items-center gap-3">
            <template x-if="limit > 0">
                <span class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">Batas RAB: <strong x-text="limit"></strong> pegawai</span>
            </template>
            <button type="button" @click="add()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm" :disabled="!canAdd()" :class="{'opacity-50 cursor-not-allowed': !canAdd()}" title="Tambah Peserta"><i class="fas fa-plus mr-1"></i>Tambah</button>
        </div>
    </div>
    <p class="text-xs text-red-600 mb-2" x-show="warning" x-text="warning"></p>
    @error('participants')
        <p class="text-xs text-red-600 mb-2">{{ $message }}</p>
    @enderror
    <template x-if="rows.length === 0">
        <div class="text-gray-500 border border-dashed rounded-lg p-6">Belum ada peserta.</div>
    </template>
    <div class="space-y-4">
        <template x-for="(row, idx) in rows" :key="idx">
            <div class="border rounded-xl p-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pegawai</label>
                        <select class="rounded-lg border-gray-300 w-full" :name="`participants[${idx}][employee_id]`" x-model="row.employee_id" required>
                            <option value="">Pilih Pegawai</option>
                            @foreach ($employees as $e)
                                <option value="{{ $e->id }}">{{ $e->nama }}{{ $e->pangkat_golongan ? ' ('.$e->pangkat_golongan.')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Sebagai</label>
                        <select class="rounded-lg border-gray-300 w-full" :name="`participants[${idx}][role]`" x-model="row.role">
                            <option value="">Pilih</option>
                            <option value="PJ">PJ</option>
                            <option value="PENDAMPING">PENDAMPING</option>
                            <option value="ANGGOTA">ANGGOTA</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pinjam Nama (ASN)</label>
                        <select class="rounded-lg border-gray-300 w-full" :name="`participants[${idx}][borrowed_employee_id]`" x-model="row.borrowed_employee_id">
                            <option value="">Tidak Pinjam</option>
                            @foreach ($employees as $e)
                                <option value="{{ $e->id }}">{{ $e->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan</label>
                    <input type="text" class="rounded-lg border-gray-300 w-full" :name="`participants[${idx}][note]`" x-model="row.note" placeholder="Contoh: Honorer meminjam nama ASN Bapak/Ibu ...">
                </div>
                <div class="mt-3 text-right">
                    <button type="button" @click="remove(idx)" class="text-red-600 hover:text-red-800 text-sm"><i class="fas fa-trash mr-1"></i>Hapus</button>
                </div>
            </div>
        </template>
    </div>
    <script>
        function poaParticipants(initialLimit) {
            return {
                rows: @json($initialParticipants ?? []),
                limit: initialLimit || 0,
                warning: '',
                init() {
                    if (this.limit > 0 && this.rows.length > this.limit) {
                        this.warning = `Jumlah peserta saat ini (${this.rows.length}) melebihi kuota RAB ${this.limit}. Hapus peserta yang tidak diperlukan.`;
                    }
                    this.registerUpdater();
                },
                registerUpdater() {
                    const self = this;
                    window.updatePoaParticipantLimit = function(newLimit) {
                        const limit = parseInt(newLimit || 0, 10) || 0;
                        self.limit = limit;
                        if (limit > 0 && self.rows.length > limit) {
                            self.warning = `Jumlah peserta saat ini (${self.rows.length}) melebihi kuota RAB ${limit}. Hapus peserta yang tidak diperlukan.`;
                        } else {
                            self.warning = '';
                        }
                    };
                },
                canAdd() {
                    return this.limit === 0 || this.rows.length < this.limit;
                },
                add() {
                    if (!this.canAdd()) {
                        this.warning = `Maksimal ${this.limit} peserta sesuai RAB.`;
                        return;
                    }
                    this.warning = '';
                    this.rows.push({ employee_id:'', role:'', borrowed_employee_id:'', note:'' });
                },
                remove(i) {
                    this.rows.splice(i,1);
                    if (this.limit > 0 && this.rows.length <= this.limit) {
                        this.warning = '';
                    }
                }
            }
        }
    </script>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rabSelect = document.getElementById('rab_id');
    const yearSelect = document.getElementById('poa_year');
    const kegiatanField = document.getElementById('kegiatan_field');
    const plannedTotal = document.getElementById('planned_total');
    const totalOcc = document.getElementById('total_occurrences');
    const participantLimitInput = document.getElementById('participant_limit');

    function fetchRabBasic() {
        const rabId = rabSelect.value;
        if (!rabId) return;
        fetch(`{{ url('/api/rabs') }}/${rabId}/basic`)
            .then(r => r.json())
            .then(({data}) => {
                if (!data) return;
                kegiatanField.value = data.kegiatan || '';
                if (plannedTotal && (!plannedTotal.value || parseFloat(plannedTotal.value) <= 0)) {
                    plannedTotal.value = parseFloat(data.total || 0);
                }
                const occ = parseInt(data.estimated_occurrences || 0);
                if (occ > 0) {
                    totalOcc.value = occ;
                }
                const limit = parseInt(data.participant_limit || 0, 10);
                if (!Number.isNaN(limit)) {
                    participantLimitInput.value = limit;
                    if (typeof window.updatePoaParticipantLimit === 'function') {
                        window.updatePoaParticipantLimit(limit);
                    }
                }
            })
            .catch(() => {});
    }

    rabSelect?.addEventListener('change', fetchRabBasic);
    if (rabSelect?.value) fetchRabBasic();

    async function refreshRabsByYear() {
        if (!yearSelect) return;
        const y = parseInt(yearSelect.value || '{{ (int) (old('year', $poa->year ?? date('Y'))) }}', 10) || new Date().getFullYear();
        const includeRabId = '{{ old('rab_id', $poa->rab_id ?? '') }}';
        try {
            const url = new URL(`{{ url('/api/poa/available-rabs') }}`);
            url.searchParams.set('year', String(y));
            if (includeRabId) url.searchParams.set('include_rab_id', String(includeRabId));
            const res = await fetch(url.toString());
            const json = await res.json();
            const data = json?.data || [];
            const current = rabSelect?.value || '';
            rabSelect.innerHTML = '';
            const opt0 = document.createElement('option');
            opt0.value = '';
            opt0.textContent = 'Pilih RAB';
            rabSelect.appendChild(opt0);
            let keepSelected = false;
            data.forEach((row) => {
                const opt = document.createElement('option');
                opt.value = String(row.id);
                opt.textContent = row.label;
                if (String(row.id) === String(current)) {
                    opt.selected = true;
                    keepSelected = true;
                }
                rabSelect.appendChild(opt);
            });
            if (!keepSelected) {
                kegiatanField.value = '';
                plannedTotal && (plannedTotal.value = '0');
            }
        } catch (e) {}
    }

    yearSelect?.addEventListener('change', () => {
        refreshRabsByYear().then(() => fetchRabBasic());
    });
    // Initial refresh to ensure list respects selected year
    refreshRabsByYear().then(() => fetchRabBasic());

    // Auto distribute
    document.getElementById('autoDistributeBtn')?.addEventListener('click', function(){
        const occ = parseInt(totalOcc.value || '0');
        const total = parseFloat(plannedTotal.value || '0');
        const monthInputs = document.querySelectorAll('#scheduleGrid input[name$="[count]"]');
        const amountInputs = document.querySelectorAll('#scheduleGrid input[name$="[amount]"]');

        // Step 1: distribute counts evenly as before
        const perMonth = occ > 0 ? Math.floor(occ/12) : 0;
        const extra = occ > 0 ? occ % 12 : 0;
        const counts = [];
        monthInputs.forEach((input, idx) => {
            let c = perMonth + (idx < extra ? 1 : 0);
            input.value = c;
            counts[idx] = c;
        });

        // Step 2: smart amount distribution based on targets × peserta × tarif tetap (70k/150k), scaled to plannedTotal
        const rabId = (document.getElementById('rab_id')?.value || '').trim();
        const peserta = parseInt((document.getElementById('participant_limit')?.value || '0'), 10) || 0;
        if (!rabId || !peserta) {
            // Fallback to classic amount distribution when data is insufficient
            const amtPerOcc = occ > 0 ? (total/occ) : 0;
            amountInputs.forEach((el, idx) => {
                if (!el) return;
                el.value = (counts[idx] * amtPerOcc).toFixed(2);
            });
            return;
        }

        // Fetch targets and compute scaled amounts
        const url = `${window.location.origin}/api/rabs/${encodeURIComponent(rabId)}/targets`;
        fetch(url)
            .then(r => r.json())
            .then(json => {
                const data = json?.data || {};
                const tDarat = parseInt(data.target_darat || 0, 10) || 0;
                const tSeberang = parseInt(data.target_seberang || 0, 10) || 0;
                if (!tDarat && !tSeberang) {
                    // Fallback classic
                    const amtPerOcc = occ > 0 ? (total/occ) : 0;
                    amountInputs.forEach((el, idx) => { if (el) el.value = (counts[idx] * amtPerOcc).toFixed(2); });
                    return;
                }
                const transport = 70000; // SPPT/SPPD transport unit
                const perDiem = 150000;   // SPPD per diem unit
                const perOccPerPeserta = (transport * (tDarat + tSeberang)) + (perDiem * tSeberang);
                const raw = counts.map(c => c * peserta * perOccPerPeserta);
                const sumRaw = raw.reduce((a,b) => a + b, 0);
                const scale = (sumRaw > 0 && total > 0) ? (total / sumRaw) : 1.0;
                amountInputs.forEach((el, idx) => {
                    if (!el) return;
                    el.value = (raw[idx] * scale).toFixed(2);
                });
            })
            .catch(() => {
                // Fallback classic on error
                const amtPerOcc = occ > 0 ? (total/occ) : 0;
                amountInputs.forEach((el, idx) => { if (el) el.value = (counts[idx] * amtPerOcc).toFixed(2); });
            });
    });
    document.getElementById('clearScheduleBtn')?.addEventListener('click', function(){
        const monthInputs = document.querySelectorAll('#scheduleGrid input');
        monthInputs.forEach((i)=> i.value = 0);
        totalOcc.value = 0;
    });
});
</script>
<input type="hidden" id="participant_limit" value="{{ (int) ($participantLimit ?? 0) }}">
