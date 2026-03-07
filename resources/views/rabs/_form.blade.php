@php
    $defaultTypes = [
        ['type' => 'transport_darat', 'label' => 'Transport Darat', 'default_price' => 70000, 'factors' => [
            ['key' => 'orang', 'label' => 'Orang', 'value' => 1],
            ['key' => 'hari', 'label' => 'Hari', 'value' => 1],
            ['key' => 'desa', 'label' => 'Desa', 'value' => 1],
            ['key' => 'kali', 'label' => 'Kali Kegiatan', 'value' => 1],
        ]],
        ['type' => 'transport_laut', 'label' => 'Transport Laut/Seberang', 'default_price' => 70000, 'factors' => [
            ['key' => 'orang', 'label' => 'Orang', 'value' => 1],
            ['key' => 'hari', 'label' => 'Hari', 'value' => 1],
            ['key' => 'desa', 'label' => 'Desa', 'value' => 1],
            ['key' => 'kali', 'label' => 'Kali Kegiatan', 'value' => 1],
        ]],
        ['type' => 'transport_harian', 'label' => 'Transport Harian', 'default_price' => 70000, 'factors' => [
            ['key' => 'orang', 'label' => 'Orang', 'value' => 1],
            ['key' => 'hari', 'label' => 'Hari', 'value' => 1],
            ['key' => 'desa', 'label' => 'Desa', 'value' => 1],
            ['key' => 'kali', 'label' => 'Kali Kegiatan', 'value' => 1],
        ]],
        ['type' => 'uang_harian', 'label' => 'Uang Harian', 'default_price' => 150000, 'factors' => [
            ['key' => 'orang', 'label' => 'Orang', 'value' => 1],
            ['key' => 'hari', 'label' => 'Hari', 'value' => 1],
            ['key' => 'desa', 'label' => 'Desa', 'value' => 1],
            ['key' => 'kali', 'label' => 'Kali Kegiatan', 'value' => 1],
        ]],
        ['type' => 'snack', 'label' => 'Snack', 'default_price' => 24000, 'factors' => [
            ['key' => 'dos', 'label' => 'Dos', 'value' => 1],
            ['key' => 'desa', 'label' => 'Desa', 'value' => 1],
            ['key' => 'kali', 'label' => 'Kali Kegiatan', 'value' => 1],
        ]],
        ['type' => 'penggandaan', 'label' => 'Penggandaan', 'default_price' => 750, 'factors' => [
            ['key' => 'lembar', 'label' => 'Lembar', 'value' => 1],
            ['key' => 'desa', 'label' => 'Desa', 'value' => 1],
            ['key' => 'kali', 'label' => 'Kali Kegiatan', 'value' => 1],
        ]],
        ['type' => 'transport_peserta', 'label' => 'Transport Peserta', 'default_price' => 70000, 'factors' => [
            ['key' => 'peserta', 'label' => 'Peserta', 'value' => 1],
            ['key' => 'desa', 'label' => 'Desa', 'value' => 1],
            ['key' => 'kali', 'label' => 'Kali Kegiatan', 'value' => 1],
        ]],
        ['type' => 'konsumsi', 'label' => 'Konsumsi', 'default_price' => 59000, 'factors' => [
            ['key' => 'porsi', 'label' => 'Porsi', 'value' => 1],
            ['key' => 'desa', 'label' => 'Desa', 'value' => 1],
            ['key' => 'kali', 'label' => 'Kali Kegiatan', 'value' => 1],
        ]],
        ['type' => 'bahan_makanan', 'label' => 'Pembelian Bahan Makanan', 'default_price' => 750000, 'factors' => [
            ['key' => 'paket', 'label' => 'Paket', 'value' => 1],
            ['key' => 'kegiatan', 'label' => 'Kegiatan', 'value' => 1],
        ]],
        ['type' => 'lainnya', 'label' => 'Lainnya', 'factors' => []],
    ];
    // Siapkan nilai awal items agar @json sederhana dan aman
    $initialItems = old('items');
    if (!$initialItems && isset($rab)) {
        $initialItems = $rab->items->map(function($i) {
            return [
                'label' => $i->label,
                'type' => $i->type,
                'unit_price' => (float) $i->unit_price,
                'factors' => collect($i->factors ?? [])->map(function($f){
                    return [
                        'key' => $f['key'] ?? ($f['label'] ?? ''),
                        'label' => $f['label'] ?? ($f['key'] ?? ''),
                        'value' => (float) ($f['value'] ?? 0),
                    ];
                })->values()->all(),
            ];
        })->values()->all();
    }
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Komponen</label>
        @php($allComponents = isset($components) ? $components : \App\Models\Rab::components())
        <select name="komponen" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            <option value="">Pilih Komponen</option>
            @foreach ($allComponents as $key => $label)
                <option value="{{ $label }}" {{ old('komponen', $rab->komponen ?? '') === $label ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('komponen')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div x-data="rabMasterSelector()" class="md:col-span-2">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Rincian Menu</label>
                <div class="flex gap-2">
                    <select x-model="rab_menu_id" @change="syncMenuName()" class="mt-1 block flex-1 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih berdasarkan Komponen</option>
                        <template x-for="m in menus" :key="m.id">
                            <option :value="m.id" x-text="m.name"></option>
                        </template>
                    </select>
                    <button type="button" @click="showAddMenu = true" class="mt-1 px-3 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-xl flex-shrink-0" title="Tambah Menu Baru">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <!-- Inline Add Menu Form -->
                <div x-show="showAddMenu" x-transition class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-semibold text-blue-800">Tambah Menu Baru</h4>
                        <button type="button" @click="showAddMenu = false" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="flex gap-2">
                        <input x-model="newMenuName" type="text" placeholder="Nama menu baru" class="flex-1 px-3 py-2 border border-blue-300 rounded-lg text-sm">
                        <button type="button" @click="addNewMenu()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">
                            Tambah
                        </button>
                    </div>
                    <div x-show="menuMessage" x-text="menuMessage" class="mt-2 text-sm" :class="menuError ? 'text-red-600' : 'text-green-600'"></div>
                </div>

                <input type="hidden" name="rab_menu_id" :value="rab_menu_id">
                <input type="hidden" name="rincian_menu" :value="rincian_menu">
                <input type="hidden" name="new_menu_name" :value="newMenuName">
                @error('rincian_menu')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kegiatan</label>
                <div class="flex gap-2">
                    <select x-model="rab_kegiatan_id" @change="syncKegiatanName()" class="mt-1 block flex-1 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih berdasarkan Rincian Menu</option>
                        <template x-for="k in kegiatans" :key="k.id">
                            <option :value="k.id" x-text="k.name"></option>
                        </template>
                    </select>
                    <button type="button" @click="showAddKegiatan = true" :disabled="!rab_menu_id" class="mt-1 px-3 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-xl flex-shrink-0 disabled:bg-gray-100 disabled:text-gray-400" title="Tambah Kegiatan Baru">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <!-- Inline Add Kegiatan Form -->
                <div x-show="showAddKegiatan" x-transition class="mt-3 p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-semibold text-green-800">Tambah Kegiatan Baru</h4>
                        <button type="button" @click="showAddKegiatan = false" class="text-green-600 hover:text-green-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="flex gap-2">
                        <input x-model="newKegiatanName" type="text" placeholder="Nama kegiatan baru" class="flex-1 px-3 py-2 border border-green-300 rounded-lg text-sm">
                        <button type="button" @click="addNewKegiatan()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm">
                            Tambah
                        </button>
                    </div>
                    <div x-show="kegiatanMessage" x-text="kegiatanMessage" class="mt-2 text-sm" :class="kegiatanError ? 'text-red-600' : 'text-green-600'"></div>
                </div>

                <input type="hidden" name="rab_kegiatan_id" :value="rab_kegiatan_id">
                <input type="hidden" name="kegiatan" :value="kegiatan">
                <input type="hidden" name="new_kegiatan_name" :value="newKegiatanName">
                @error('kegiatan')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-2">Anda bisa memilih dari data yang ada atau menambahkan menu/kegiatan baru langsung di sini.</p>
        <script>
            function rabMasterSelector() {
                return {
                    menus: [],
                    kegiatans: [],
                    rab_menu_id: @json(old('rab_menu_id', $rab->rab_menu_id ?? '')),
                    rab_kegiatan_id: @json(old('rab_kegiatan_id', $rab->rab_kegiatan_id ?? '')),
                    rincian_menu: @json(old('rincian_menu', $rab->rincian_menu ?? '')),
                    kegiatan: @json(old('kegiatan', $rab->kegiatan ?? '')),
                    // UI state for inline creation
                    showAddMenu: false,
                    showAddKegiatan: false,
                    newMenuName: '',
                    newKegiatanName: '',
                    menuMessage: '',
                    menuError: false,
                    kegiatanMessage: '',
                    kegiatanError: false,
                    init() {
                        this.$watch('rab_menu_id', (val) => {
                            if (!val) { this.kegiatans = []; this.rab_kegiatan_id=''; this.kegiatan=''; return; }
                            fetch(`{{ route('rab-kegiatans.by-menu') }}?rab_menu_id=${val}`)
                                .then(r => r.json()).then(d => { this.kegiatans = d.data || []; });
                        });

                        // Watch new menu name changes
                        this.$watch('newMenuName', () => {
                            this.syncMenuName();
                        });

                        // Watch new kegiatan name changes
                        this.$watch('newKegiatanName', () => {
                            this.syncKegiatanName();
                        });

                        // load menus by current component
                        this.loadMenus();
                        if (this.rab_menu_id) {
                            fetch(`{{ route('rab-kegiatans.by-menu') }}?rab_menu_id=${this.rab_menu_id}`)
                                .then(r => r.json()).then(d => { this.kegiatans = d.data || []; });
                        }
                    },
                    loadMenus() {
                        const select = document.querySelector('select[name="komponen"]');
                        if (!select) return;
                        const selectedText = select.value; // label
                        // map label->key on server side via dataset embedded below
                        const mapping = @json(array_flip(\App\Models\Rab::components()));
                        const key = mapping[selectedText] || '';
                        if (!key) { this.menus = []; this.rab_menu_id=''; this.kegiatans=[]; this.rab_kegiatan_id=''; return; }
                        fetch(`{{ route('rab-menus.by-component') }}?component_key=${key}`)
                            .then(r => r.json()).then(d => { this.menus = d.data || []; });
                    },
                    syncMenuName() {
                        const m = this.menus.find(x => String(x.id) === String(this.rab_menu_id));
                        this.rincian_menu = m ? m.name : '';
                        // If no menu selected but new menu name exists, use it
                        if (!this.rab_menu_id && this.newMenuName.trim()) {
                            this.rincian_menu = this.newMenuName.trim();
                        }
                    },
                    syncKegiatanName() {
                        const k = this.kegiatans.find(x => String(x.id) === String(this.rab_kegiatan_id));
                        this.kegiatan = k ? k.name : '';
                        // If no kegiatan selected but new kegiatan name exists, use it
                        if (!this.rab_kegiatan_id && this.newKegiatanName.trim()) {
                            this.kegiatan = this.newKegiatanName.trim();
                        }
                    },
                    async addNewMenu() {
                        if (!this.newMenuName.trim()) {
                            this.menuMessage = 'Nama menu tidak boleh kosong';
                            this.menuError = true;
                            return;
                        }

                        const select = document.querySelector('select[name="komponen"]');
                        const selectedText = select.value;
                        const mapping = @json(array_flip(\App\Models\Rab::components()));
                        const component_key = mapping[selectedText] || '';

                        if (!component_key) {
                            this.menuMessage = 'Pilih komponen terlebih dahulu';
                            this.menuError = true;
                            return;
                        }

                        try {
                            const response = await fetch('{{ route('rab-menus.store') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    component_key: component_key,
                                    name: this.newMenuName.trim()
                                })
                            });

                            const data = await response.json();

                            if (response.ok) {
                                // Add to menus list
                                this.menus.push({
                                    id: data.menu.id,
                                    name: data.menu.name
                                });

                                // Select the new menu
                                this.rab_menu_id = data.menu.id;
                                this.rincian_menu = data.menu.name;
                                this.newMenuName = '';
                                this.showAddMenu = false;
                                this.menuMessage = 'Menu berhasil ditambahkan';
                                this.menuError = false;

                                // Load kegiatans for new menu
                                fetch(`{{ route('rab-kegiatans.by-menu') }}?rab_menu_id=${data.menu.id}`)
                                    .then(r => r.json()).then(d => { this.kegiatans = d.data || []; });

                                setTimeout(() => this.menuMessage = '', 3000);
                            } else {
                                this.menuMessage = data.message || 'Gagal menambahkan menu';
                                this.menuError = true;
                            }
                        } catch (error) {
                            this.menuMessage = 'Terjadi kesalahan saat menambahkan menu';
                            this.menuError = true;
                        }
                    },
                    async addNewKegiatan() {
                        if (!this.newKegiatanName.trim()) {
                            this.kegiatanMessage = 'Nama kegiatan tidak boleh kosong';
                            this.kegiatanError = true;
                            return;
                        }

                        if (!this.rab_menu_id) {
                            this.kegiatanMessage = 'Pilih menu terlebih dahulu';
                            this.kegiatanError = true;
                            return;
                        }

                        try {
                            const response = await fetch('{{ route('rab-kegiatans.store') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    rab_menu_id: this.rab_menu_id,
                                    names: [this.newKegiatanName.trim()]
                                })
                            });

                            const data = await response.json();

                            if (response.ok) {
                                // Add to kegiatans list
                                this.kegiatans.push({
                                    id: data.kegiatan_id,
                                    name: this.newKegiatanName.trim()
                                });

                                // Select the new kegiatan
                                this.rab_kegiatan_id = data.kegiatan_id;
                                this.kegiatan = this.newKegiatanName.trim();
                                this.newKegiatanName = '';
                                this.showAddKegiatan = false;
                                this.kegiatanMessage = 'Kegiatan berhasil ditambahkan';
                                this.kegiatanError = false;

                                setTimeout(() => this.kegiatanMessage = '', 3000);
                            } else {
                                this.kegiatanMessage = data.message || 'Gagal menambahkan kegiatan';
                                this.kegiatanError = true;
                            }
                        } catch (error) {
                            this.kegiatanMessage = 'Terjadi kesalahan saat menambahkan kegiatan';
                            this.kegiatanError = true;
                        }
                    }
                }
            }
        </script>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    // Setup event listener for komponen change
    const komponenSelect = document.querySelector('select[name="komponen"]');
    const masterSelector = document.querySelector('[x-data="rabMasterSelector()"]');

    if (komponenSelect && masterSelector) {
        komponenSelect.addEventListener('change', () => {
            const alpineData = Alpine.$data(masterSelector);
            if (alpineData) {
                alpineData.loadMenus();
                alpineData.rab_menu_id = '';
                alpineData.kegiatans = [];
                alpineData.rab_kegiatan_id = '';
                alpineData.rincian_menu = '';
                alpineData.kegiatan = '';
            }
        });
    }
});
</script>

<div class="mt-8" x-data="rabBuilder()">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">Item Kegiatan</h3>
        <div class="flex gap-2">
            <select x-model="newItemType" class="rounded-lg border-gray-300">
                <option value="">Pilih Tipe Item</option>
                @foreach ($defaultTypes as $t)
                    <option value='@json($t)'>{{ $t['label'] }}</option>
                @endforeach
            </select>
            <button type="button" @click="addPresetItem()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Tambah</button>
            <button type="button" @click="addBlankItem()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Item Kosong</button>
        </div>
    </div>

    <template x-if="items.length === 0">
        <div class="text-gray-500 border border-dashed rounded-lg p-6">Belum ada item. Tambahkan item terlebih dahulu.</div>
    </template>

    <div class="space-y-4">
        <template x-for="(item, idx) in items" :key="idx">
            <div class="border rounded-xl p-4">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Item</label>
                        <input class="w-full rounded-lg border-gray-300" type="text" :name="`items[${idx}][label]`" x-model="item.label" placeholder="cth: Transport Darat" required>
                        <input type="hidden" :name="`items[${idx}][type]`" x-model="item.type">
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Satuan (Rp)</label>
                        <input class="w-full rounded-lg border-gray-300" type="number" step="0.01" min="0" :name="`items[${idx}][unit_price]`" x-model.number="item.unit_price" required>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Sub Total (auto)</label>
                        <div class="w-full px-3 py-2 bg-gray-50 rounded-lg border border-gray-200" x-text="formatRupiah(subtotal(item))"></div>
                    </div>
                    <div class="md:col-span-1 flex md:justify-end gap-2">
                        <button type="button" @click="removeItem(idx)" class="bg-red-50 text-red-700 hover:bg-red-100 px-3 py-2 rounded-lg">Hapus</button>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-semibold text-gray-800">Faktor Perhitungan</h4>
                        <button type="button" @click="addFactor(idx)" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded">Tambah Faktor</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3" >
                        <template x-for="(f, fi) in item.factors" :key="fi">
                            <div class="grid grid-cols-12 gap-2 items-end">
                                <div class="col-span-5">
                                    <label class="block text-xs text-gray-600 mb-1">Nama Faktor</label>
                                    <input class="w-full rounded-lg border-gray-300" type="text" :name="`items[${idx}][factors][${fi}][label]`" x-model="f.label" placeholder="cth: Orang">
                                    <input type="hidden" :name="`items[${idx}][factors][${fi}][key]`" x-model="f.key">
                                </div>
                                <div class="col-span-5">
                                    <label class="block text-xs text-gray-600 mb-1">Nilai</label>
                                    <input class="w-full rounded-lg border-gray-300" type="number" step="0.01" min="0" :name="`items[${idx}][factors][${fi}][value]`" x-model.number="f.value">
                                </div>
                                <div class="col-span-2 flex justify-end">
                                    <button type="button" @click="removeFactor(idx, fi)" class="text-sm text-red-600 hover:underline">Hapus</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div class="mt-6 flex items-center justify-between">
        <div class="text-lg font-semibold">Jumlah Total: <span x-text="formatRupiah(grandTotal())"></span></div>
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl">Simpan RAB</button>
    </div>

    <script>
        function rabBuilder() {
            return {
                items: @json($initialItems ?? []),
                newItemType: '',
                addPresetItem() {
                    if (!this.newItemType) return;
                    const preset = JSON.parse(this.newItemType);
                    this.items.push({
                        label: preset.label,
                        type: preset.type,
                        unit_price: preset.default_price || 0,
                        factors: (preset.factors || []).map(f => ({...f}))
                    });
                    this.newItemType = '';
                },
                addBlankItem() {
                    this.items.push({ label: 'Item', type: 'lainnya', unit_price: 0, factors: [] });
                },
                removeItem(idx) { this.items.splice(idx, 1); },
                addFactor(idx) {
                    this.items[idx].factors.push({ key: 'f' + Date.now(), label: 'Faktor', value: 1 });
                },
                removeFactor(idx, fi) { this.items[idx].factors.splice(fi, 1); },
                subtotal(item) {
                    const qty = (item.factors || []).reduce((acc, f) => acc * (parseFloat(f.value || 0) || 0), 1);
                    return qty * (parseFloat(item.unit_price || 0) || 0);
                },
                grandTotal() { return this.items.reduce((s, it) => s + this.subtotal(it), 0); },
                formatRupiah(n) {
                    try {
                        return 'Rp ' + (n||0).toLocaleString('id-ID', { maximumFractionDigits: 0 });
                    } catch (e) { return 'Rp ' + Math.round(n||0).toString(); }
                }
            }
        }
    </script>
</div>
