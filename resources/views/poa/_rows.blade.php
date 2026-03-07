@foreach ($poas as $poa)
    @php
        $selectedM = isset($selectedMonth) ? (int)$selectedMonth : null;
        $userEmpId = auth()->user()?->employee_id;
        $isRelated = false;
        if ($selectedM && $userEmpId) {
            $months = $poa->schedule['months'] ?? [];
            $meta = $months[$selectedM] ?? [];
            $pids = collect($meta['participant_ids'] ?? [])->map(fn($id)=>(int)$id)->filter()->unique()->values()->all();
            $general = $poa->participants->pluck('employee_id')->filter()->map(fn($id)=>(int)$id)->unique()->values()->all();
            $effective = !empty($pids) ? $pids : $general;
            $isRelated = in_array((int)$userEmpId, $effective, true);
        }
        $schedule = $poa->schedule ?: [];
        $months = $schedule['months'] ?? [];
        $meta = $selectedM ? ($months[$selectedM] ?? []) : [];
        $pids = collect($meta['participant_ids'] ?? [])->map(fn($id)=>(int)$id)->filter()->unique()->values()->all();
        $general = $poa->participants->pluck('employee_id')->filter()->map(fn($id)=>(int)$id)->unique()->values()->all();
        $effective = !empty($pids) ? $pids : $general;
        $assigned = $userEmpId && in_array((int)$userEmpId, $effective, true);
        $claimed = !empty($meta['claimed_at']) || !empty($meta['sppt_lpj_id']) || !empty($meta['sppd_lpj_id']);
        $nameClass = $assigned ? ($claimed ? 'text-green-700 font-medium' : 'text-red-600 font-semibold') : 'text-indigo-700';
    @endphp
    <tr>
        <td class="px-3 py-2 sm:px-6 sm:py-3 text-sm text-gray-900 relative">
            @php
                $showUrl = route('poa.show', $poa);
                if ($selectedM) {
                    $showUrl .= '?month=' . (int) $selectedM . '&auto_open=1';
                }
                $participantNames = collect($poa->hover_participants ?? [])
                    ->filter()->values();
                $targets = $poa->hover_targets ?? ['darat' => null, 'seberang' => null];
                $optItems = collect($poa->hover_optional_items ?? [])->filter()->values();
                $moreCount = max(0, $participantNames->count() - 6);
            @endphp
            <span class="inline-flex items-center gap-2 js-poa-hover group">
                <a href="{{ $showUrl }}" class="js-poa-hover-trigger {{ $nameClass }} relative transition-all duration-200 ease-out group-hover:-translate-y-0.5 group-hover:text-indigo-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400 focus-visible:ring-offset-2 after:content-[''] after:absolute after:left-0 after:-bottom-0.5 after:h-0.5 after:w-full after:rounded-full after:bg-gradient-to-r after:from-indigo-500 after:via-purple-500 after:to-pink-500 after:scale-x-0 after:origin-left after:transition-transform after:duration-300 group-hover:after:scale-x-100">{{ $poa->kegiatan }}</a>
                <button type="button" class="sm:hidden text-indigo-600 hover:text-indigo-800 js-poa-info-btn" aria-label="Lihat ringkasan kegiatan">
                    <i class="fas fa-info-circle"></i>
                </button>
                <!-- Template content for hover/tap preview (used by JS) -->
                <div class="hidden js-hover-template">
                    <div class="rounded-xl shadow-2xl border border-gray-200 bg-white overflow-hidden">
                        <div class="px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                            <div class="text-sm font-semibold">Ringkasan Kegiatan</div>
                            <div class="text-[11px] text-white/80">Informasi cepat untuk pratayang</div>
                        </div>
                        <div class="p-4 text-gray-800 text-xs">
                            <div class="mb-2">
                                <div class="text-[11px] text-gray-500 mb-1">Pegawai Terlibat</div>
                                @if($participantNames->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($participantNames->take(6) as $nm)
                                            <span class="px-2 py-0.5 rounded-full bg-gray-100 border border-gray-200">{{ $nm }}</span>
                                        @endforeach
                                        @if($moreCount > 0)
                                            <span class="px-2 py-0.5 rounded-full bg-gray-100 border border-gray-200">+{{ $moreCount }} lainnya</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-gray-500">-</div>
                                @endif
                            </div>
                            <div class="mb-2">
                                <div class="text-[11px] text-gray-500 mb-1">Alokasi Desa</div>
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200">Darat: <strong class="ml-1">{{ is_null($targets['darat'] ?? null) ? '-' : (int) $targets['darat'] }}</strong></span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-teal-50 text-teal-700 border border-teal-200">Seberang: <strong class="ml-1">{{ is_null($targets['seberang'] ?? null) ? '-' : (int) $targets['seberang'] }}</strong></span>
                                </div>
                            </div>
                            <div>
                                <div class="text-[11px] text-gray-500 mb-1">Item Tambahan</div>
                                @if($optItems->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($optItems->take(8) as $lbl)
                                            <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200">{{ $lbl }}</span>
                                        @endforeach
                                        @if(($optItems->count() - 8) > 0)
                                            <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200">+{{ $optItems->count() - 8 }} lainnya</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-gray-500">-</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </span>
            @if(auth()->check() && auth()->user()->isSuperAdmin())
                @php $monthLabels=[1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des']; @endphp
                <div class="mt-2 flex flex-wrap items-center gap-1.5 text-[10px]">
                    @foreach ($monthLabels as $m => $ml)
                        @php
                            $schedule = $poa->schedule ?: [];
                            $months = $schedule['months'] ?? [];
                            $meta = $months[$m] ?? [];
                            $c = (int) ($meta['count'] ?? 0);
                            $locked = (bool) ($meta['locked'] ?? false);
                            $daratTarget = (int) ($targets['darat'] ?? 0);
                            $seberangTarget = (int) ($targets['seberang'] ?? 0);
                            $dv = $meta['darat_village_ids'] ?? null;
                            $sv = $meta['seberang_village_ids'] ?? null;
                            $requireSppt = is_array($dv) ? (count($dv) > 0) : ($daratTarget > 0);
                            $requireSppd = is_array($sv) ? (count($sv) > 0) : ($seberangTarget > 0);
                            $spptComplete = !$requireSppt || (!empty($meta['no_surat_sppt']) && !empty($meta['tanggal_kegiatan_sppt']) && !empty($meta['tanggal_surat_sppt']));
                            $sppdComplete = !$requireSppd || (!empty($meta['no_surat_sppd']) && !empty($meta['tanggal_kegiatan_sppd']) && !empty($meta['tanggal_surat_sppd']));
                            $haveParticipants = (!empty($meta['participant_ids']) && count((array)$meta['participant_ids']) > 0) || ($poa->participants && $poa->participants->count() > 0);
                            $complete = ($c > 0) && $haveParticipants && $spptComplete && $sppdComplete;
                            $baseUrl = route('poa.show', $poa) . '?month=' . (int) $m . '&auto_open=1';
                            $title = $complete ? 'Lengkap' : ($c>0 ? 'Perlu dilengkapi' : 'Belum dijadwalkan');
                            if ($locked) { $title .= ' • Terkunci'; }
                            $cls = 'inline-flex items-center px-1.5 py-0.5 rounded border';
                            if ($complete) {
                                $cls .= ' bg-emerald-600 text-white border-emerald-600';
                            } elseif ($c > 0) {
                                $cls .= ' bg-amber-50 text-amber-700 border-amber-300';
                            } else {
                                $cls .= ' bg-gray-50 text-gray-500 border-gray-200';
                            }
                            if ($locked) { $cls .= ' opacity-70'; }
                        @endphp
                        <a href="{{ $baseUrl }}" class="{{ $cls }}" title="{{ $title }}">
                            <span class="font-medium">{{ $ml }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </td>
        <td class="px-3 py-2 sm:px-6 sm:py-3 text-sm text-gray-900 text-right">Rp {{ number_format($poa->planned_total, 0, ',', '.') }}</td>
        @if(auth()->check() && auth()->user()->isSuperAdmin())
            <td class="px-3 py-2 sm:px-6 sm:py-3 text-sm text-gray-900 text-right">
                <div class="inline-flex gap-2">
                    <a href="{{ $showUrl }}" class="text-indigo-600 hover:text-indigo-800">Lihat</a>
                    <a href="{{ route('poa.edit', $poa) }}" class="text-yellow-600 hover:text-yellow-700">Edit</a>
                    <form action="{{ route('poa.destroy', $poa) }}" method="POST" onsubmit="return confirm('Yakin hapus POA ini?')" class="inline">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:text-red-800">Hapus</button>
                    </form>
                </div>
            </td>
        @endif
    </tr>
@endforeach
