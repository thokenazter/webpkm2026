<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-start md:items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold mb-1">RAB BOK Puskesmas</h1>
                            <p class="text-indigo-100">Kelola Rencana Anggaran Biaya kegiatan</p>
                        </div>
                        <div class="mt-4 md:mt-0 flex gap-2">
                            @if(auth()->user()?->isSuperAdmin())
                                <a href="{{ route('rabs.export-master-template') }}" class="inline-flex items-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                                    <i class="fas fa-file-excel mr-2"></i>Export Template
                                </a>
                                <a href="{{ route('rabs.export-all-templated') }}" class="inline-flex items-center bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                                    <i class="fas fa-file-excel mr-2"></i>Export All
                                </a>
                                <a href="{{ route('rabs.export-puskesmas-template') }}" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                                    <i class="fas fa-building mr-2"></i>Export Puskesmas
                                </a>
                                <a href="#" id="btnExportSelected" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                                    <i class="fas fa-file-export mr-2"></i>Export Selected
                                </a>
                                <a href="{{ route('rabs.create') }}" class="inline-flex items-center bg-white text-indigo-700 px-4 py-2 rounded-lg font-medium hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <i class="fas fa-plus mr-2"></i>Buat RAB
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">{{ session('error') }}</div>
            @endif

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 mb-8">
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Komponen</label>
                            <select name="komponen" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Komponen</option>
                                @foreach ($componentsList as $c)
                                    <option value="{{ $c }}" {{ ($selectedKomponen ?? '') === $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Rincian Menu</label>
                            <select name="menu" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Rincian Menu</option>
                                @foreach ($menuList as $m)
                                    <option value="{{ $m }}" {{ ($selectedMenu ?? '') === $m ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kegiatan</label>
                            <select name="kegiatan" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Kegiatan</option>
                                @foreach ($kegiatanList as $k)
                                    <option value="{{ $k }}" {{ ($selectedKegiatan ?? '') === $k ? 'selected' : '' }}>{{ $k }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="h-10 mt-6 inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-4 rounded-xl"><i class="fas fa-filter mr-2"></i>Terapkan</button>
                            <a href="{{ route('rabs.index') }}" class="h-10 mt-6 inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 rounded-xl"><i class="fas fa-rotate-left mr-2"></i>Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <div class="lg:col-span-1 bg-white rounded-2xl shadow-lg border border-gray-100 p-5">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <i class="fas fa-chart-pie text-indigo-600 text-sm"></i>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900">Distribusi per Komponen</h3>
                    </div>
                    <div class="h-72 sm:h-80">
                        <canvas id="rabByComponentChart"></canvas>
                    </div>
                </div>
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100 p-5">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                            <i class="fas fa-chart-bar text-purple-600 text-sm"></i>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900">Top 10 Kegiatan (Total RAB)</h3>
                    </div>
                    <div class="h-72 sm:h-80">
                        <canvas id="rabByKegiatanChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100" x-data="{
                    q: '',
                    toggleSearch(){
                        const visible = Array.from($root.querySelectorAll('.searchable-row')).filter(row => !row.classList.contains('hidden'));
                        return visible;
                    }
                }"
                x-init="q = $refs.searchInput ? $refs.searchInput.value : ''">
                <div class="p-6">
                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Search Bar -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between gap-3 flex-wrap">
                            <h3 class="text-lg font-semibold text-gray-900">Daftar RAB</h3>
                            <div class="flex items-center gap-2 w-full md:w-auto">
                                <form method="GET" action="{{ route('rabs.index') }}" class="flex-1 md:flex-initial">
                                    <div class="relative">
                                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                        <input x-ref="searchInput" name="q" value="{{ $q ?? request('q') }}" x-model.debounce.250ms="q" type="text" placeholder="Cari komponen / rincian menu / kegiatan..." class="w-full md:w-80 pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto" id="rabTable">
                            <thead>
                                <tr class="bg-gray-50">
                                    @if(auth()->user()?->isSuperAdmin())
                                        <th class="px-4 py-3 text-left">
                                            <input type="checkbox" id="selectAllRabs" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                        </th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Komponen</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rincian Menu</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kegiatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($rabs as $rab)
                                    @php
                                        $searchKey = trim(($rab->komponen ?? '') . ' ' . ($rab->rincian_menu ?? '') . ' ' . ($rab->kegiatan ?? ''));
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition duration-150 searchable-row" :class="(($el.dataset.key||'').toLowerCase().includes((q||'').toLowerCase())) ? '' : 'hidden'" data-key="{{ e($searchKey) }}">
                                        @if(auth()->user()?->isSuperAdmin())
                                            <td class="px-4 py-4">
                                                <input type="checkbox" class="rab-select rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" value="{{ $rab->id }}" />
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $rab->komponen }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $rab->rincian_menu }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $rab->kegiatan }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">Rp {{ number_format($rab->total, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <div class="inline-flex items-center gap-1">
                                                <a href="{{ route('rabs.show', $rab) }}" class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 transition" title="Lihat">
                                                    <i class="fas fa-eye mr-1"></i>Lihat
                                                </a>
                                                @if(auth()->user()?->isSuperAdmin())
                                                    <a href="{{ route('rabs.edit', $rab) }}" class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium text-yellow-700 bg-yellow-50 hover:bg-yellow-100 border border-yellow-200 transition" title="Edit">
                                                        <i class="fas fa-edit mr-1"></i>Edit
                                                    </a>
                                                    <a href="{{ route('rabs.export', $rab) }}" class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 transition" title="Export">
                                                        <i class="fas fa-file-excel mr-1"></i>Export
                                                    </a>
                                                    <form action="{{ route('rabs.destroy', $rab) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus RAB ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 transition" title="Hapus">
                                                            <i class="fas fa-trash mr-1"></i>Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-6 text-center text-gray-500">Belum ada RAB</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $rabs->links() }}</div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const byComponent = @json(($byComponent ?? collect())->map(fn($i) => ['label' => $i->komponen, 'total' => (float)$i->total]));
        const byKegiatan = @json(($byKegiatan ?? collect())->map(fn($i) => ['label' => $i->kegiatan, 'total' => (float)$i->total]));

        const fmtRp = (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(v));
        const truncate = (s, max) => s && s.length > max ? s.substring(0, max) + '…' : s;

        const palette = ['#4F46E5','#9333EA','#22C55E','#F59E0B','#EF4444','#06B6D4','#8B5CF6','#10B981','#F97316','#3B82F6'];
        const componentColors = {
            'Peningkatan Layanan Kesehatan Sesuai Siklus Hidup': '#EC4899',
            'Surveilans, respons penyakit dan kesehatan lingkungan': '#06B6D4',
            'Pemberian Makanan Tambahan (PMT) berbahan pangan lokal': '#F97316',
            'MANAGEMEN PUSKESMAS': '#6366F1',
            'INSENTIF UKM': '#10B981',
        };

        // Doughnut — Distribusi per Komponen
        (function(){
            const el = document.getElementById('rabByComponentChart'); if (!el) return;
            const labels = byComponent.map(i => i.label);
            const data = byComponent.map(i => i.total);
            const total = data.reduce((a,b) => a+b, 0);
            new Chart(el.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: labels.map((l, i) => componentColors[l] ?? palette[i % palette.length]),
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '55%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12, boxHeight: 12, padding: 10,
                                font: { size: 10 },
                                generateLabels: (chart) => {
                                    const ds = chart.data.datasets[0];
                                    return chart.data.labels.map((label, i) => ({
                                        text: truncate(label, 30),
                                        fillStyle: ds.backgroundColor[i],
                                        strokeStyle: '#fff',
                                        lineWidth: 1,
                                        index: i,
                                        hidden: false,
                                    }));
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    const val = ctx.parsed;
                                    const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                    return ' ' + ctx.label + ': ' + fmtRp(val) + ' (' + pct + '%)';
                                }
                            }
                        }
                    }
                }
            });
        })();

        // Horizontal bar — Top 10 Kegiatan
        (function(){
            const el = document.getElementById('rabByKegiatanChart'); if (!el) return;
            const labels = byKegiatan.map(i => i.label);
            const data = byKegiatan.map(i => i.total);
            const maxVal = Math.max(...data, 1);
            const bgColors = data.map((v) => {
                const ratio = v / maxVal;
                const alpha = 0.15 + ratio * 0.45;
                return `rgba(99, 102, 241, ${alpha})`;
            });
            new Chart(el.getContext('2d'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Total',
                        data,
                        backgroundColor: bgColors,
                        borderColor: '#6366F1',
                        borderWidth: 1,
                        borderRadius: 4,
                        barPercentage: 0.7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title: (items) => items[0]?.label || '',
                                label: (ctx) => ' ' + fmtRp(ctx.parsed.x),
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                font: { size: 11 },
                                callback: function(value) {
                                    const label = this.getLabelForValue(value);
                                    return truncate(label, window.innerWidth < 768 ? 18 : 35);
                                }
                            },
                            grid: { display: false }
                        },
                        x: {
                            beginAtZero: true,
                            ticks: {
                                font: { size: 10 },
                                callback: (v) => fmtRp(v),
                            },
                            grid: { color: 'rgba(0,0,0,0.04)' }
                        }
                    }
                }
            });
        })();

        // Export Selected logic
        (function(){
            const btn = document.getElementById('btnExportSelected');
            const selectAll = document.getElementById('selectAllRabs');
            const getChecks = () => Array.from(document.querySelectorAll('.rab-select'));
            if (selectAll) {
                selectAll.addEventListener('change', () => {
                    getChecks().forEach(cb => cb.checked = selectAll.checked);
                });
            }
            if (btn) {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const ids = getChecks().filter(cb => cb.checked).map(cb => cb.value);
                    if (!ids.length) { alert('Pilih minimal 1 kegiatan.'); return; }
                    const url = '{{ route('rabs.export-multiple-stacked') }}' + '?ids=' + encodeURIComponent(ids.join(',')) + '&sort=grouped';
                    window.location.href = url;
                });
            }
        })();
    </script>
</x-app-layout>
