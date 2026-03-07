<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold mb-1">Detail POA</h1>
                        <p class="text-indigo-100">Rencana tindakan tahunan untuk kegiatan</p>
                    </div>
                    <div class="flex gap-2">
                        @if(!empty($userIsAdmin))
                            <a href="{{ route('poa.edit', $poa) }}" class="inline-flex items-center bg-yellow-400 hover:bg-yellow-500 text-gray-900 px-4 py-2 rounded-lg font-medium shadow-sm transition-colors duration-200"><i class="fas fa-edit mr-2"></i>Edit</a>
                        @endif
                        <a href="{{ route('poa.index') }}" class="inline-flex items-center bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <div class="space-y-3">
                        <div>
                            <div class="text-xs text-gray-500">Tahun</div>
                            <div class="text-lg font-semibold">{{ $poa->year }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Nomor Surat</div>
                            <div class="text-lg font-semibold">{{ $poa->nomor_surat ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Kegiatan</div>
                            <div class="text-lg font-semibold">{{ $poa->kegiatan }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">RAB</div>
                            <div class="text-sm">#{{ $poa->rab_id }} - {{ $poa->rab?->rincian_menu }} • {{ $poa->rab?->komponen }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Pagu Tahunan</div>
                            <div class="text-sm">{{ $poa->budget ? ($poa->budget->year . ' - Rp ' . number_format($poa->budget->amount, 0, ',', '.')) : '-' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Planned Total</div>
                            <div class="text-lg font-semibold">Rp {{ number_format($poa->planned_total, 0, ',', '.') }}</div>
                        </div>
                        <div class="pt-2 border-t border-gray-100"></div>
                        <div>
                            <div class="text-xs text-gray-500">Alokasi Kegiatan ({{ $poa->year }})</div>
                            <div class="text-lg font-semibold">{{ is_null($allocatedAmount) ? '-' : 'Rp ' . number_format($allocatedAmount, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Realisasi (LPJ)</div>
                            <div class="text-lg font-semibold">Rp {{ number_format($realizedAmount ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Sisa Alokasi</div>
                            @php $rem = $remainingAmount; @endphp
                            <div class="text-lg font-semibold {{ (isset($rem) && $rem < 0) ? 'text-red-600' : 'text-green-700' }}">{{ is_null($rem) ? '-' : 'Rp ' . number_format($rem, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Output/Target</h3>
                    <div class="whitespace-pre-wrap text-gray-800">{{ $poa->output_target ?: '-' }}</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Jadwal Pelaksanaan</h3>
                @if(!empty($userIsAdmin))
                <div class="flex items-center gap-3 text-xs text-gray-600 mb-4">
                    <span class="inline-flex items-center"><span class="w-3 h-3 inline-block rounded-full ring-2 ring-indigo-400 bg-white mr-1"></span>Jalan (LPJ)</span>
                    <span class="inline-flex items-center"><span class="w-3 h-3 inline-block rounded-full ring-2 ring-green-500 bg-white mr-1"></span>Lengkap</span>
                    <span class="inline-flex items-center"><span class="w-3 h-3 inline-block rounded-full ring-2 ring-amber-400 bg-white mr-1"></span>Perlu LPJ</span>
                    <span class="inline-flex items-center"><span class="w-3 h-3 inline-block rounded-full ring-2 ring-red-500 bg-white mr-1"></span>Terkunci</span>
                </div>
                @endif
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    @php $months=[1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des']; @endphp
                    @foreach ($months as $m => $label)
                        @php
                            $c = (int) ($poa->schedule['months'][$m]['count'] ?? 0);
                            $amt = (float) ($poa->schedule['months'][$m]['amount'] ?? 0);
                            $real = isset($executed) ? ((int) ($executed[$m] ?? 0)) : 0;
                            $detail = isset($executedDetails) ? ($executedDetails[$m] ?? null) : null;
                            $marked = (bool) ($poa->schedule['months'][$m]['marked'] ?? false);
                            $locked = (bool) ($poa->schedule['months'][$m]['locked'] ?? false);
                            // Determine completion status: nomor + tanggal + peserta lengkap
                            $meta = $poa->schedule['months'][$m] ?? [];
                            $targets = $rabTargets ?? ['darat' => null, 'seberang' => null];
                            $jumlahDarat = is_array($meta['darat_village_ids'] ?? null) ? count($meta['darat_village_ids']) : (int) ($targets['darat'] ?? 0);
                            $jumlahSeberang = is_array($meta['seberang_village_ids'] ?? null) ? count($meta['seberang_village_ids']) : (int) ($targets['seberang'] ?? 0);
                            $requireSppt = $jumlahDarat > 0;
                            $requireSppd = $jumlahSeberang > 0;
                            $spptComplete = !$requireSppt || (!empty($meta['no_surat_sppt']) && !empty($meta['tanggal_kegiatan_sppt']) && !empty($meta['tanggal_surat_sppt']));
                            $sppdComplete = !$requireSppd || (!empty($meta['no_surat_sppd']) && !empty($meta['tanggal_kegiatan_sppd']) && !empty($meta['tanggal_surat_sppd']));
                            $haveParticipants = (!empty($meta['participant_ids']) && count((array)$meta['participant_ids']) > 0) || ($poa->participants && $poa->participants->count() > 0);
                            $complete = ($c > 0) && $haveParticipants && $spptComplete && $sppdComplete;
                        @endphp
                        <div class="poa-month-card relative border rounded-xl p-3 transition-all duration-200 hover:shadow-lg {{ $c>0 ? 'bg-green-50 border-green-200' : '' }} {{ $real>0 ? 'ring-2 ring-indigo-400' : (($userIsAdmin && $complete) ? 'ring-2 ring-green-500' : ($marked ? 'ring-2 ring-amber-400' : '')) }}" 
                             x-data="{open:false}"
                             @if(request()->boolean('auto_open') && (int) request('month') === (int) $m) x-init="open=true" @endif
                             data-month="{{ $m }}"
                             data-real="{{ $real>0 ? 1 : 0 }}"
                             data-marked="{{ $marked ? 1 : 0 }}"
                             data-complete="{{ $complete ? 1 : 0 }}"
                             data-locked="{{ $locked ? 1 : 0 }}"
                             @click="open=true">
                            <div class="flex items-center justify-between mb-1">
                                <div class="text-sm font-medium text-gray-700">{{ $label }}</div>
                                <div data-status-badge>
                                    @if($real>0)
                                        <div class="text-indigo-600 text-xs inline-flex items-center animate-pulse"><i class="fas fa-check-circle mr-1"></i>Jalan</div>
                                    @elseif($userIsAdmin && $complete)
                                        <div class="text-green-700 text-xs inline-flex items-center animate-pulse"><i class="fas fa-check mr-1"></i>Lengkap</div>
                                    @elseif($marked)
                                        <div class="text-amber-600 text-xs inline-flex items-center animate-pulse"><i class="fas fa-clock mr-1"></i>Perlu LPJ</div>
                                    @elseif($locked)
                                        <div class="text-red-600 text-xs inline-flex items-center"><i class="fas fa-lock mr-1"></i>Terkunci</div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-xs text-gray-600">Rencana: <span class="font-semibold">{{ $c }}</span></div>
                            <div class="text-xs text-gray-600">Anggaran: <span class="font-semibold">Rp {{ number_format($amt, 0, ',', '.') }}</span></div>
                            @if(!empty($optionalItemsLabels))
                                <div class="text-xs text-gray-600 mt-1">
                                    Keterangan Item: 
                                    @php $lbl = collect($optionalItemsLabels); @endphp
                                    <span class="font-semibold">{{ $lbl->take(5)->implode(', ') }}@if($lbl->count()>5) dan {{ $lbl->count()-5 }} lainnya @endif</span>
                                </div>
                            @endif

                            @if(auth()->check() && auth()->user()->isSuperAdmin())
                                @php $claimedHidden = (bool) ($claimStatus[$m]['claimed_hidden'] ?? false); @endphp
                                <div class="mt-3 p-2 bg-gray-50 border border-gray-200 rounded-lg" @click.stop>
                                    <div class="grid grid-cols-2">
                                        <form method="POST" action="{{ route('poa.schedule.toggle_mark', $poa) }}" class="js-ajax-form" data-kind="mark">
                                            @csrf
                                            <input type="hidden" name="month" value="{{ $m }}">
                                            <input type="hidden" name="marked" value="{{ $marked ? 0 : 1 }}">
                                            <button class="w-full {{ $marked ? 'bg-amber-600 hover:bg-amber-700' : 'bg-amber-500 hover:bg-amber-600' }} text-white px-2 py-1.5 rounded text-xs font-medium">{{ $marked ? 'ⓧ' : '✔️' }}</button>
                                        </form>
                                        <form method="POST" action="{{ route('poa.schedule.toggle_claim_label', $poa) }}" class="js-ajax-form" data-kind="claim_label">
                                            @csrf
                                            <input type="hidden" name="month" value="{{ $m }}">
                                            <input type="hidden" name="hidden" value="{{ $claimedHidden ? 0 : 1 }}">
                                            <button class="w-full {{ $claimedHidden ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-emerald-500 hover:bg-emerald-600' }} text-white px-2 py-1.5 rounded text-xs font-medium">{{ $claimedHidden ? '✅' : '🚫' }}</button>
                                        </form>
                                    </div>
                                    <div class="mt-2">
                                        <form method="POST" action="{{ route('poa.schedule.toggle_claim_lock', $poa) }}" class="js-ajax-form" data-kind="claim_lock">
                                            @csrf
                                            <input type="hidden" name="month" value="{{ $m }}">
                                            <input type="hidden" name="locked" value="{{ $locked ? 0 : 1 }}">
                                            <button class="w-full {{ $locked ? 'bg-red-600 hover:bg-red-700' : 'bg-red-500 hover:bg-red-600' }} text-white px-2 py-1.5 rounded text-xs font-medium">
                                                <i class="fas fa-lock{{ $locked ? '-open' : '' }} mr-1"></i>
                                                {{ $locked ? 'Buka Klaim' : 'Kunci Klaim' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                            @if($real>0)
                                <div class="text-xs text-gray-600 mt-1">Realisasi: <span class="font-semibold">{{ $real }}</span> LPJ</div>
                                @php 
                                    $realAmt = isset($executedSums) ? (float) ($executedSums[$m] ?? 0) : 0; 
                                    $progAmt = isset($progressSums) ? (float) ($progressSums[$m] ?? 0) : 0; 
                                    $effectiveAmt = $realAmt + $progAmt; 
                                    $delta = $effectiveAmt - $amt; 
                                @endphp
                                <div class="text-xs mt-1">
                                    <span class="text-gray-600">Terserap:</span>
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($effectiveAmt, 0, ',', '.') }}</span>
                                    @if($amt > 0)
                                        @if($delta < 0)
                                            <span class="ml-2 inline-flex items-center text-amber-600 bg-amber-50 px-2 py-0.5 rounded">Sisa Rp {{ number_format(abs($delta), 0, ',', '.') }}</span>
                                        @elseif($delta > 0)
                                            <span class="ml-2 inline-flex items-center text-red-600 bg-red-50 px-2 py-0.5 rounded">Lebih Rp {{ number_format($delta, 0, ',', '.') }}</span>
                                        @else
                                            <span class="ml-2 inline-flex items-center text-green-700 bg-green-50 px-2 py-0.5 rounded">Sesuai Rencana</span>
                                        @endif
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Professional Modal Popup -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 @click.away="open=false"
                                 @keydown.escape.window="open=false"
                                 class="fixed inset-0 z-50 flex items-center justify-center px-4"
                                 style="display: none;">
                                <!-- Backdrop -->
                                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
                                
                                <!-- Modal Content -->
                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95"
                                     @click.stop
                                     class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
                                    
                                    <!-- Modal Header -->
                                    <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 flex items-center justify-between">
                                        <div>
                                            <h3 class="text-xl font-bold">Detail Bulan {{ $label }} {{ $poa->year }}</h3>
                                            <p class="text-indigo-100 text-sm mt-1">Informasi lengkap pelaksanaan kegiatan</p>
                                        </div>
                                        <button @click="open=false" class="text-white/80 hover:text-white transition-colors duration-200 p-2 hover:bg-white/10 rounded-lg">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Modal Body -->
                                    <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]">
                                        <!-- Summary Cards -->
                                        <div class="grid grid-cols-3 gap-4 mb-6">
                                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                                                <div class="flex items-center justify-between mb-2">
                                                    <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                                                    <span class="text-2xl font-bold text-blue-900">{{ $c }}</span>
                                                </div>
                                                <p class="text-sm text-blue-700 font-medium">Kegiatan Direncanakan</p>
                                            </div>
                                            
                                            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                                                <div class="flex items-center justify-between mb-2">
                                                    <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                                                    <span class="text-lg font-bold text-green-900">Rp {{ number_format($amt, 0, ',', '.') }}</span>
                                                </div>
                                                <p class="text-sm text-green-700 font-medium">Anggaran Dialokasikan</p>
                                            </div>
                                            
                                            @if($real>0)
                                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                                                <div class="flex items-center justify-between mb-2">
                                                    <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                                                    <span class="text-2xl font-bold text-purple-900">{{ $real }}</span>
                                                </div>
                                                <p class="text-sm text-purple-700 font-medium">LPJ Terealisasi</p>
                                            </div>
                                            @else
                                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                                                <div class="flex items-center justify-between mb-2">
                                                    <i class="fas fa-hourglass-half text-gray-500 text-xl"></i>
                                                    <span class="text-2xl font-bold text-gray-700">0</span>
                                                </div>
                                                <p class="text-sm text-gray-600 font-medium">Belum Ada Realisasi</p>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        @if($real>0)
                                            @php 
                                                $realAmt = isset($executedSums) ? (float) ($executedSums[$m] ?? 0) : 0; 
                                                $progAmt = isset($progressSums) ? (float) ($progressSums[$m] ?? 0) : 0; 
                                                $effectiveAmt = $realAmt + $progAmt; 
                                                $delta = $effectiveAmt - $amt;
                                                $percentage = $amt > 0 ? ($effectiveAmt / $amt * 100) : 0;
                                            @endphp
                                            
                                            <!-- Progress Bar Section -->
                                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-5 mb-6 border border-gray-200">
                                                <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                                    <i class="fas fa-chart-line mr-2 text-indigo-600"></i>
                                                    Progress Penyerapan Anggaran
                                                </h4>
                                                <div class="relative">
                                                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                                                        <span>Terserap: <strong class="text-gray-900">Rp {{ number_format($effectiveAmt, 0, ',', '.') }}</strong></span>
                                                        <span>Target: <strong class="text-gray-900">Rp {{ number_format($amt, 0, ',', '.') }}</strong></span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                                        <div class="h-full rounded-full transition-all duration-500 ease-out flex items-center justify-end pr-2
                                                            {{ $percentage > 100 ? 'bg-gradient-to-r from-red-500 to-red-600' : ($percentage == 100 ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-blue-500 to-indigo-600') }}"
                                                            style="width: {{ min($percentage, 100) }}%">
                                                            <span class="text-xs text-white font-bold">{{ number_format($percentage, 1) }}%</span>
                                                    </div>
                                                    </div>
                                                    <div class="mt-3 flex items-center justify-center">
                                                        @if($delta < 0)
                                                            <span class="inline-flex items-center text-amber-700 bg-amber-100 px-4 py-2 rounded-lg font-medium">
                                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                                Sisa Anggaran: Rp {{ number_format(abs($delta), 0, ',', '.') }}
                                                            </span>
                                                        @elseif($delta > 0)
                                                            <span class="inline-flex items-center text-red-700 bg-red-100 px-4 py-2 rounded-lg font-medium">
                                                                <i class="fas fa-exclamation-circle mr-2"></i>
                                                                Kelebihan: Rp {{ number_format($delta, 0, ',', '.') }}
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center text-green-700 bg-green-100 px-4 py-2 rounded-lg font-medium">
                                                                <i class="fas fa-check-circle mr-2"></i>
                                                                Penyerapan Sesuai Rencana
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            
                                            <!-- LPJ Details -->
                                            @if($detail && is_array($detail['lpjs']) && count($detail['lpjs']))
                                                <div class="mb-6">
                                                    <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                                                        <i class="fas fa-list-alt mr-2 text-indigo-600"></i>
                                                        Detail Laporan Pertanggungjawaban (LPJ)
                                                    </h4>
                                                    <div class="space-y-3">
                                                        @foreach($detail['lpjs'] as $idx => $entry)
                                                            <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow duration-200">
                                                                @php
                                                                    $lpjId = $entry['id'] ?? null;
                                                                    $lpjOwner = $entry['created_by'] ?? null;
                                                                    $canDownload = $lpjId && ($userIsAdmin || (auth()->id() && (int) auth()->id() === (int) $lpjOwner));
                                                                @endphp
                                                                <div class="flex items-start justify-between mb-3">
                                                                    <div class="flex items-center">
                                                                        <span class="bg-indigo-100 text-indigo-700 text-sm font-bold px-3 py-1 rounded-lg mr-3">
                                                                            #{{ $idx + 1 }}
                                                                        </span>
                                                                        <div>
                                                                            <h5 class="font-semibold text-gray-800">{{ $entry['type'] ?? '-' }}</h5>
                                                                            <p class="text-sm text-gray-500 mt-1">
                                                                                <i class="far fa-calendar mr-1"></i>
                                                                                {{ $entry['tanggal'] ?? '-' }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <div class="text-lg font-bold text-green-700">
                                                                            Rp {{ number_format((float) ($entry['amount'] ?? 0), 0, ',', '.') }}
                                                                        </div>
                                                                        @if($canDownload)
                                                                            <a href="{{ route('lpj.download', $lpjId) }}" class="inline-flex items-center text-xs text-indigo-700 hover:text-indigo-900 mt-1">
                                                                                <i class="fas fa-download mr-1"></i>Unduh
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="grid md:grid-cols-2 gap-3 mt-3 pt-3 border-t border-gray-100">
                                                                    <div>
                                                                        <p class="text-sm font-medium text-gray-600 mb-1">
                                                                            <i class="fas fa-users mr-1 text-blue-500"></i>
                                                                            Peserta
                                                                        </p>
                                                                        <p class="text-sm text-gray-700">
                                                                            @php $names = collect($entry['participants'] ?? [])->filter()->values(); @endphp
                                                                            {{ $names->take(3)->implode(', ') }}
                                                                            @if($names->count()>3)
                                                                                <span class="text-gray-500">dan {{ $names->count()-3 }} lainnya</span>
                                                                            @endif
                                                                        </p>
                                                                    </div>
                                                                    
                                                                    @php $vils = collect($entry['villages'] ?? [])->filter()->values(); @endphp
                                                                    @if($vils->count())
                                                                    <div>
                                                                        <p class="text-sm font-medium text-gray-600 mb-1">
                                                                            <i class="fas fa-map-marker-alt mr-1 text-red-500"></i>
                                                                            Lokasi
                                                                        </p>
                                                                        <p class="text-sm text-gray-700">
                                                                            {{ $vils->take(3)->implode(', ') }}
                                                                            @if($vils->count()>3)
                                                                                <span class="text-gray-500">dan {{ $vils->count()-3 }} lainnya</span>
                                                                            @endif
                                                                        </p>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="bg-gray-50 rounded-xl p-8 text-center">
                                                <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                                <p class="text-gray-500 font-medium">Belum ada realisasi LPJ untuk bulan ini</p>
                                            </div>
                                        @endif
                                        @php
                                            $meta = $poa->schedule['months'][$m] ?? [];
                                            $claimInfo = $claimStatus[$m] ?? [];
                                            $dvIds = $meta['darat_village_ids'] ?? [];
                                            $svIds = $meta['seberang_village_ids'] ?? [];
                                            $dDates = $meta['darat_dates'] ?? [];
                                            $sDates = $meta['seberang_dates'] ?? [];
                                        @endphp
                                        @if($userIsAdmin)
                                            <div class="mt-6">
                                                <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                                    <i class="fas fa-sliders-h mr-2 text-indigo-600"></i>
                                                    Pengaturan Bulan (Nomor & Tanggal)
                                                </h4>
                                                <form method="POST" action="{{ route('poa.schedule.upsert_month', $poa) }}" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    @csrf
                                                    <input type="hidden" name="month" value="{{ $m }}">
                                                    <div>
                                                        <label class="block text-xs text-gray-600 mb-1">Nomor Surat SPPT</label>
                                                        <input name="no_surat_sppt" value="{{ $meta['no_surat_sppt'] ?? '' }}" class="w-full rounded-lg border-gray-300" placeholder="cth: 003">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs text-gray-600 mb-1">Nomor Surat SPPD</label>
                                                        <input name="no_surat_sppd" value="{{ $meta['no_surat_sppd'] ?? '' }}" class="w-full rounded-lg border-gray-300" placeholder="cth: 003">
                                                    </div>
                                                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                                                        <div>
                                                            <label class="block text-xs text-gray-600 mb-1">Tanggal Kegiatan SPPT (teks)</label>
                                                            <input x-ref="tglKegiatanSppt" name="tanggal_kegiatan_sppt" value="{{ $meta['tanggal_kegiatan_sppt'] ?? '' }}" class="w-full rounded-lg border-gray-300" placeholder="cth: 14 dan 18 Juni 2025">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs text-gray-600 mb-1">Tanggal Kegiatan SPPD (teks)</label>
                                                            <input x-ref="tglKegiatanSppd" name="tanggal_kegiatan_sppd" value="{{ $meta['tanggal_kegiatan_sppd'] ?? '' }}" class="w-full rounded-lg border-gray-300" placeholder="cth: 20 s/d 22 Juni 2025">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs text-gray-600 mb-1">Tanggal Surat SPPT</label>
                                                            <input x-ref="tglSuratSppt" name="tanggal_surat_sppt" value="{{ $meta['tanggal_surat_sppt'] ?? '' }}" class="w-full rounded-lg border-gray-300" placeholder="cth: 10 Juni 2025">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs text-gray-600 mb-1">Tanggal Surat SPPD</label>
                                                            <input x-ref="tglSuratSppd" name="tanggal_surat_sppd" value="{{ $meta['tanggal_surat_sppd'] ?? '' }}" class="w-full rounded-lg border-gray-300" placeholder="cth: 12 Juni 2025">
                                                        </div>
                                                        <div class="md:col-span-2">
                                                            <p class="text-xs text-gray-500 mt-1">Atau pilih per-desa di bawah ini untuk otomatisasi tanggal.</p>
                                                        </div>
                                                    </div>

                                                    <!-- Custom Calendar & Holiday Picker -->
                                                    @php
                                                        $year = (int) $poa->year;
                                                        $monthIndex = (int) $m;
                                                        $monthNames = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                                                        $monthName = $monthNames[$monthIndex] ?? '';
                                                        $startOfMonth = sprintf('%04d-%02d-01', $year, $monthIndex);
                                                        $endOfMonth = (new DateTime($startOfMonth))->modify('last day of this month')->format('Y-m-d');
                                                        $existingHolidays = \App\Models\GlobalHoliday::whereBetween('date', [$startOfMonth, $endOfMonth])->pluck('date')->toArray();
                                                        $holidayDays = collect($existingHolidays)->map(function($ymd){
                                                            try { return (int) (new DateTime($ymd))->format('j'); } catch (Exception $e) { return null; }
                                                        })->filter()->values()->all();
                                                    @endphp
                                                    @php
                                                        // Build previous month info for holiday lookup
                                                        $prevMonthStart = (new DateTime($startOfMonth))->modify('first day of previous month')->format('Y-m-01');
                                                        $prevMonthEnd = (new DateTime($startOfMonth))->modify('last day of previous month')->format('Y-m-d');
                                                        $prevHolidays = \App\Models\GlobalHoliday::whereBetween('date', [$prevMonthStart, $prevMonthEnd])->pluck('date')->toArray();
                                                    @endphp
                                                    <div class="md:col-span-2" x-data='calendarManager({
                                                        year: {{ $year }}, month: {{ $monthIndex }}, monthName: "{{ $monthName }}",
                                                        initialHolidayDays: @json($holidayDays),
                                                        holidayYmdCurrent: @json($existingHolidays),
                                                        holidayYmdPrev: @json($prevHolidays),
                                                        limitSppt: {{ (int) ($rabTargets['darat'] ?? 0) }},
                                                        limitSppd: {{ (int) ($rabTargets['seberang'] ?? 0) }}
                                                    })'>
                                                        <input type="hidden" name="holidays" :value="holidayCsv" />
                                                        <div class="grid grid-cols-1 gap-3">
                                                            <div>
                                                                <h5 class="font-semibold text-gray-800 mb-2 flex flex-wrap items-center gap-2">
                                                                    <i class="fas fa-calendar-day text-red-500 mr-1"></i>
                                                                    Kalender Bulan Ini
                                                                    <span class="text-xs text-gray-500 font-normal">(klik untuk menandai Tanggal Merah atau memilih tanggal sesuai mode)</span>
                                                                </h5>
                                                                <div class="flex flex-wrap gap-2 mb-2">
                                                                    <button type="button" class="text-xs px-3 py-1.5 rounded border" :class="mode==='holiday' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-700 border-gray-300'" @click="mode='holiday'">Tanggal Merah</button>
                                                                    <button type="button" class="text-xs px-3 py-1.5 rounded border" :class="mode==='kegiatan_sppt' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300'" @click="mode='kegiatan_sppt'">Kegiatan SPPT</button>
                                                                    <button type="button" class="text-xs px-3 py-1.5 rounded border" :class="mode==='kegiatan_sppd' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-700 border-gray-300'" @click="mode='kegiatan_sppd'">Kegiatan SPPD</button>
                                                                    <button type="button" class="text-xs px-3 py-1.5 rounded border" :class="mode==='surat_sppt' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300'" @click="mode='surat_sppt'">Surat Keluar SPPT</button>
                                                                    <button type="button" class="text-xs px-3 py-1.5 rounded border" :class="mode==='surat_sppd' ? 'bg-teal-600 text-white border-teal-600' : 'bg-white text-gray-700 border-gray-300'" @click="mode='surat_sppd'">Surat Keluar SPPD</button>
                                                                    <span class="flex-1"></span>
                                                                    <span class="text-xs text-gray-500">Mode: <span class="font-medium" x-text="labelMode"></span></span>
                                                                </div>

                                                                <div class="text-xs text-gray-700 font-medium mb-1">{{ $monthName }} {{ $year }}</div>
                                                                <div class="flex items-center gap-3 text-[11px] text-gray-600 mb-1">
                                                                    <span class="inline-flex items-center"><span class="w-3 h-3 inline-block bg-indigo-200 border border-indigo-400 rounded mr-1"></span>SPPT</span>
                                                                    <span class="inline-flex items-center"><span class="w-3 h-3 inline-block bg-green-200 border border-green-400 rounded mr-1"></span>SPPD</span>
                                                                    <span class="inline-flex items-center"><span class="w-3 h-3 inline-block bg-red-200 border border-red-400 rounded mr-1"></span>Tanggal Merah</span>
                                                                </div>
                                                                <template x-if="warnMessage">
                                                                    <div class="mb-2 text-xs text-red-600">⚠️ <span x-text="warnMessage"></span></div>
                                                                </template>
                                                                <div class="grid grid-cols-7 text-center text-xs text-gray-500 mb-1">
                                                                    <template x-for="w in weekdayNames" :key="'w-'+w"><div class="py-1" x-text="w"></div></template>
                                                                </div>
                                                                <div class="grid grid-cols-7 gap-1 text-center select-none">
                                                                    <template x-for="cell in cells" :key="'cal-'+cell.key">
                                                                        <div
                                                                            @click="onCellClick(cell.day)"
                                                                            :class="cellClass(cell.day)"
                                                                            class="border rounded-md py-2">
                                                                            <span x-text="cell.day || ''"></span>
                                                                        </div>
                                                                    </template>
                                                                </div>

                                                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-gray-600">
                                                                    <div>
                                                                        <div class="font-medium text-gray-700 mb-1">Ringkas Kegiatan</div>
                                                                        <div>SPPT: <span x-text="formatKegiatanDisplay(selected.sppt)"></span></div>
                                                                        <div>SPPD: <span x-text="formatKegiatanDisplay(selected.sppd)"></span></div>
                                                                    </div>
                                                                    <div>
                                                                        <div class="font-medium text-gray-700 mb-1">Rekomendasi Surat</div>
                                                                        <div>SPPT: <span x-text="surat.spptText || '-'" class="text-blue-700"></span></div>
                                                                        <div>SPPD: <span x-text="surat.sppdText || '-'" class="text-teal-700"></span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Villages & Dates -->
                                                    @php
                                                        $year = (int) $poa->year;
                                                        $monthIndex = (int) $m;
                                                        $startDate = sprintf('%04d-%02d-01', $year, $monthIndex);
                                                        $endDate = (new DateTime($startDate))->modify('last day of this month')->format('Y-m-d');
                                                        // Prefer existing selections; otherwise, auto-fill defaults by RAB targets and configured preferred villages
                                                        $targetDarat = (int) ($rabTargets['darat'] ?? ($targets['darat'] ?? 0));
                                                        $targetSeberang = (int) ($rabTargets['seberang'] ?? ($targets['seberang'] ?? 0));
                                                        $prefDaratNames = (array) config('bok.villages.default_names.DARAT', []);
                                                        $prefSeberangNames = (array) config('bok.villages.default_names.SEBERANG', []);
                                                        $prefDaratIds = $villages->filter(function($v) use ($prefDaratNames){
                                                            $aksesOk = strtoupper($v->akses ?? '') === 'DARAT';
                                                            $name = trim(preg_replace('/^\s*Desa\s+/iu', '', (string) $v->nama));
                                                            return $aksesOk && in_array($name, $prefDaratNames, true);
                                                        })
                                                            ->pluck('id')->map(fn($id) => (int) $id)->values()->all();
                                                        $prefSeberangIds = $villages->filter(function($v) use ($prefSeberangNames){
                                                            $aksesOk = strtoupper($v->akses ?? '') === 'SEBERANG';
                                                            $name = trim(preg_replace('/^\s*Desa\s+/iu', '', (string) $v->nama));
                                                            return $aksesOk && in_array($name, $prefSeberangNames, true);
                                                        })
                                                            ->pluck('id')->map(fn($id) => (int) $id)->values()->all();
                                                        $dvIds = array_values($meta['darat_village_ids'] ?? []);
                                                        if (empty($dvIds) && $targetDarat > 0) {
                                                            $dvIds = array_slice($prefDaratIds, 0, $targetDarat);
                                                        }
                                                        $svIds = array_values($meta['seberang_village_ids'] ?? []);
                                                        if (empty($svIds) && $targetSeberang > 0) {
                                                            $svIds = array_slice($prefSeberangIds, 0, $targetSeberang);
                                                        }
                                                        $dDates = $meta['darat_dates'] ?? [];
                                                        $sDates = $meta['seberang_dates'] ?? [];
                                                        $villagesData = $villages->map(fn($v) => [
                                                            'id' => (int) $v->id,
                                                            'nama' => $v->nama,
                                                            'akses' => strtoupper($v->akses ?? ''),
                                                        ])->values();
                                                    @endphp
                                                    <input type="hidden" name="darat_village_ids" value="{{ implode(',', !empty($meta['darat_village_ids']) ? $meta['darat_village_ids'] : $dvIds) }}">
                                                    <input type="hidden" name="seberang_village_ids" value="{{ implode(',', !empty($meta['seberang_village_ids']) ? $meta['seberang_village_ids'] : $svIds) }}">
                                                    @php
                                                        $selectedIds = collect($meta['participant_ids'] ?? [])->map(fn($id) => (int) $id)->values()->all();
                                                        $employeesJson = ($employeeMap ?? collect())->map(fn($e) => ['id' => (int) $e->id, 'nama' => $e->nama, 'pangkat' => $e->pangkat_golongan])->values();
                                                    @endphp
                                                    <div class="md:col-span-2" x-data='{
                                                            open: false,
                                                            search: "",
                                                            employees: @json($employeesJson),
                                                            selected: @json($selectedIds),
                                                            borrowed: @json($meta['borrowed_map'] ?? []),
                                                            limit: {{ (int) ($participantLimit ?? 0) }},
                                                            warn: "",
                                                            toggle(id) {
                                                                id = parseInt(id);
                                                                if (this.selected.includes(id)) {
                                                                    this.selected = this.selected.filter(x => x !== id);
                                                                } else {
                                                                    if (this.limit > 0 && this.selected.length >= this.limit) {
                                                                        this.warn = `Maksimal ${this.limit} peserta sesuai RAB.`;
                                                                        setTimeout(() => { this.warn = ""; }, 3000);
                                                                        return;
                                                                    }
                                                                    this.selected = [...this.selected, id];
                                                                }
                                                            },
                                                            isChecked(id) { return this.selected.includes(parseInt(id)); },
                                                            get filtered() {
                                                                const term = (this.search || "").toLowerCase();
                                                                if (!term) return this.employees;
                                                                return this.employees.filter(e => (e.nama||"").toLowerCase().includes(term) || (e.pangkat||"").toLowerCase().includes(term));
                                                            },
                                                            get displayCount() { return (this.selected||[]).length; },
                                                            getName(id) {
                                                                id = parseInt(id);
                                                                const emp = this.employees.find(e => e.id === id);
                                                                if (!emp) return `ID ${id}`;
                                                                return emp.nama + (emp.pangkat ? ` (${emp.pangkat})` : "");
                                                            },
                                                            removeById(id) {
                                                                id = parseInt(id);
                                                                this.selected = this.selected.filter(x => x !== id);
                                                            }
                                                        }'>
                                                        <label class="block text-xs text-gray-600 mb-1">Peserta Bulan (multi-select)</label>
                                                        @if(($participantLimit ?? 0) > 0)
                                                        <div class="text-[11px] text-gray-500 mb-1">Batas sesuai RAB: <strong>{{ (int)$participantLimit }}</strong> peserta.</div>
                                                        @endif
                                                        <div class="w-full rounded-lg border border-gray-300 p-2">
                                                            <!-- Selected chips -->
                                                            <div class="flex flex-wrap gap-2 mb-2">
                                                                <template x-for="id in selected" :key="id">
                                                                    <span class="inline-flex items-center bg-indigo-50 text-indigo-700 px-2 py-1 rounded">
                                                                        <span x-text="getName(id)"></span>
                                                                        <button type="button" class="ml-2 text-indigo-700 hover:text-red-700" @click="removeById(id)">×</button>
                                                                    </span>
                                                                </template>
                                                            </div>
                                                            <!-- Dropdown trigger -->
                                                            <div class="relative" @keydown.escape.window="open=false">
                                                                <button type="button" @click="open = !open" class="w-full flex items-center justify-between rounded border border-gray-200 bg-white px-3 py-2 text-sm">
                                                                    <span class="text-gray-700">Pilih Pegawai</span>
                                                                    <span class="text-gray-500">(<span x-text="displayCount"></span> dipilih)</span>
                                                                </button>
                                                                <!-- Dropdown panel -->
                                                                <div x-show="open" x-transition @click.outside="open=false" class="absolute z-50 mt-1 w-full rounded-lg border border-gray-200 bg-white shadow-lg">
                                                                    <div class="p-2 border-b border-gray-100">
                                                                        <input type="text" x-model="search" class="w-full rounded border-gray-200" placeholder="Cari pegawai...">
                                                                    </div>
                                                                    <div class="max-h-60 overflow-auto p-1">
                                                                        <template x-for="emp in filtered" :key="emp.id">
                                                                            <label class="flex items-center gap-2 px-2 py-1 hover:bg-gray-50 cursor-pointer">
                                                                                <input type="checkbox" :value="emp.id" :checked="isChecked(emp.id)" @change="toggle(emp.id)">
                                                                                <span class="text-gray-800" x-text="emp.nama"></span>
                                                                                <span class="text-xs text-gray-500" x-text="emp.pangkat ? `(${emp.pangkat})` : ''"></span>
                                                                            </label>
                                                                        </template>
                                                                        <div class="text-xs text-gray-500 px-3 py-2" x-show="filtered.length === 0">Tidak ada hasil</div>
                                                                    </div>
                                                                    <div class="p-2 border-t border-gray-100 flex items-center justify-end gap-2">
                                                                <button type="button" class="text-xs text-gray-600 hover:text-gray-800" @click="selected = []">Bersihkan</button>
                                                                <button type="button" class="text-xs text-indigo-600 hover:text-indigo-800" @click="open=false">Selesai</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="participant_ids" :value="(selected || []).join(',')">
                                                    <!-- Per-bulan Pinjam Nama -->
                                                    <div class="mt-3">
                                                        <div class="text-xs font-semibold text-gray-700 mb-1 flex items-center">
                                                            <i class="fas fa-user-tag text-indigo-600 mr-2"></i>Pinjam Nama per Peserta (opsional)
                                                        </div>
                                                        <template x-if="(selected || []).length === 0">
                                                            <div class="text-xs text-gray-500">Tidak ada peserta dipilih.</div>
                                                        </template>
                                                        <div class="space-y-2" x-show="(selected || []).length > 0">
                                                            <template x-for="id in selected" :key="'borrow-'+id">
                                                                <div class="flex items-center gap-3">
                                                                    <div class="w-1/3 text-sm text-gray-700 truncate" x-text="getName(id)"></div>
                                                                    <div class="flex-1">
                                                                        <select class="w-full rounded-lg border-gray-300 text-sm" x-model.number="borrowed[id]">
                                                                            <option :value="null">Tidak Pinjam</option>
                                                                            <template x-for="e in employees" :key="'opt-'+e.id">
                                                                                <option :value="e.id" x-text="e.nama"></option>
                                                                            </template>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                            <input type="hidden" name="borrowed_map" :value="JSON.stringify(borrowed || {})">
                                                            <p class="text-[11px] text-gray-500">Nama dokumen SPPT/SPPD akan memakai ASN terpilih, saldo tetap masuk ke pelaksana.</p>
                                                        </div>
                                                    </div>
                                                    <template x-if="warn"><div class="mt-2 text-xs text-red-600">⚠️ <span x-text="warn"></span></div></template>
                                                        <p class="text-xs text-gray-500 mt-1">Kosongkan untuk memakai peserta POA (umum).</p>
                                                    </div>
                                                    </div>
                                                    <div class="md:col-span-2">
                                                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">Simpan Pengaturan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif

                                        @if(($claimInfo['allowed'] ?? false) || ($claimInfo['claimed'] ?? false))
                                        <!-- Klaim Kegiatan (User) | Tersedia setiap saat -->
                                            <div class="mt-6">
                                                <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                                    <i class="fas fa-file-signature mr-2 text-emerald-600"></i>
                                                    Klaim Kegiatan (Buat SPPT & SPPD)
                                                </h4>
                                                @php
                                                    $ids = (array) ($claimInfo['participant_ids'] ?? []);
                                                    $targets = $rabTargets ?? ['darat' => null, 'seberang' => null];
                                                    $jumlahDaratForm = is_array($meta['darat_village_ids'] ?? null) ? count($meta['darat_village_ids']) : (int) ($targets['darat'] ?? 0);
                                                    $jumlahSeberangForm = is_array($meta['seberang_village_ids'] ?? null) ? count($meta['seberang_village_ids']) : (int) ($targets['seberang'] ?? 0);
                                                    $requireSppt = $jumlahDaratForm > 0;
                                                    $requireSppd = $jumlahSeberangForm > 0;
                                                    $dNames = collect($dvIds)->map(fn($id) => $villages->firstWhere('id', $id)?->nama)->filter()->values()->implode(', ');
                                                    $sNames = collect($svIds)->map(fn($id) => $villages->firstWhere('id', $id)?->nama)->filter()->values()->implode(', ');
                                                    $claimedByUser = ($claimInfo['claimed_by'] ?? null) ? ($claimUsers->get($claimInfo['claimed_by']) ?? null) : null;
                                                @endphp
                                                @if(!empty($targets['darat']) || !empty($targets['seberang']))
                                                    <div class="mb-2 text-xs text-gray-600">
                                                        Target RAB:
                                                        @if(!empty($targets['darat'])) <span class="mr-3">Darat: <strong>{{ (int) $targets['darat'] }}</strong> desa</span>@endif
                                                        @if(!empty($targets['seberang'])) <span>Seberang: <strong>{{ (int) $targets['seberang'] }}</strong> desa</span>@endif
                                                    </div>
                                                @endif
                                                @if(!empty($meta['desa_tujuan_darat']) || !empty($meta['desa_tujuan_seberang']))
                                                    <div class="mb-3 text-xs text-gray-600">
                                                        @if(!empty($meta['desa_tujuan_darat']))
                                                            <div class="bg-blue-50 border border-blue-200 rounded px-3 py-2 mb-1">
                                                                <span class="font-semibold text-blue-700">SPPT:</span>
                                                                <span class="text-blue-800">{{ $meta['desa_tujuan_darat'] }}</span>
                                                            </div>
                                                        @endif
                                                        @if(!empty($meta['desa_tujuan_seberang']))
                                                            <div class="bg-purple-50 border border-purple-200 rounded px-3 py-2">
                                                                <span class="font-semibold text-purple-700">SPPD:</span>
                                                                <span class="text-purple-800">{{ $meta['desa_tujuan_seberang'] }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                                @if(($participantLimit ?? 0) > 0)
                                                    <p class="text-xs text-gray-600 mb-3">Kuota peserta RAB: <strong>{{ (int) $participantLimit }}</strong> pegawai.</p>
                                                @endif
                                                @if(!empty($claimInfo['tanggal_kegiatan_sppt']) || !empty($claimInfo['tanggal_kegiatan_sppd']) || !empty($claimInfo['tanggal_surat_sppt']) || !empty($claimInfo['tanggal_surat_sppd']))
                                                    <div class="mb-3 grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-gray-600">
                                                        @if(!empty($claimInfo['tanggal_kegiatan_sppt']))
                                                            <div class="bg-gray-50 border border-gray-200 rounded px-3 py-2">
                                                                <span class="font-semibold text-gray-700 block mb-1">Tanggal Kegiatan SPPT</span>
                                                                <span class="text-gray-600">{{ $claimInfo['tanggal_kegiatan_sppt'] }}</span>
                                                            </div>
                                                        @endif
                                                        @if(!empty($claimInfo['tanggal_surat_sppt']))
                                                            <div class="bg-blue-50 border border-blue-200 rounded px-3 py-2">
                                                                <span class="font-semibold text-blue-700 block mb-1">Tanggal Surat SPPT</span>
                                                                <span class="text-blue-800">{{ $claimInfo['tanggal_surat_sppt'] }}</span>
                                                            </div>
                                                        @endif
                                                        @if(!empty($claimInfo['tanggal_kegiatan_sppd']))
                                                            <div class="bg-gray-50 border border-gray-200 rounded px-3 py-2">
                                                                <span class="font-semibold text-gray-700 block mb-1">Tanggal Kegiatan SPPD</span>
                                                                <span class="text-gray-600">{{ $claimInfo['tanggal_kegiatan_sppd'] }}</span>
                                                            </div>
                                                        @endif
                                                        @if(!empty($claimInfo['tanggal_surat_sppd']))
                                                            <div class="bg-green-50 border border-green-200 rounded px-3 py-2">
                                                                <span class="font-semibold text-green-700 block mb-1">Tanggal Surat SPPD</span>
                                                                <span class="text-green-800">{{ $claimInfo['tanggal_surat_sppd'] }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                                @if(!empty($ids))
                                                    <div class="mb-3 text-sm text-gray-700">
                                                        <span class="font-medium">Peserta Bulan (Pelaksana → Nama Dokumen):</span>
                                                        @php
                                                            $map = (array) ($meta['borrowed_map'] ?? []);
                                                            $names = collect($ids)->map(function($id) use ($employeeMap, $map){
                                                                $e = $employeeMap[$id] ?? null;
                                                                if (!$e) return null;
                                                                $label = $e->nama;
                                                                if (!empty($e->pangkat_golongan)) {
                                                                    $label .= ' (' . $e->pangkat_golongan . ')';
                                                                }
                                                                $bid = $map[$id] ?? null;
                                                                if ($bid) {
                                                                    $b = $employeeMap[$bid] ?? null;
                                                                    if ($b) {
                                                                        $label .= ' → ' . $b->nama;
                                                                    }
                                                                }
                                                                return $label;
                                                            })->filter()->values();
                                                        @endphp
                                                        {{ $names->take(4)->implode(', ') }}@if($names->count()>4) dan {{ $names->count()-4 }} lainnya @endif
                                                    </div>
                                                @else
                                                    <div class="mb-3 text-sm text-gray-700">
                                                        <span class="font-medium">Peserta POA (umum):</span>
                                                        @php $names = $poa->participants->map(fn($p) => $p->employee?->nama)->filter()->values(); @endphp
                                                        {{ $names->take(5)->implode(', ') }}@if($names->count()>5) dan {{ $names->count()-5 }} lainnya @endif
                                                    </div>
                                                @endif
                                                @if($claimInfo['claimed'] ?? false)
                                                    <div class="mb-3 text-xs text-gray-600">
                                                        <i class="fas fa-check-circle text-emerald-500 mr-1"></i>
                                                        Sudah diklaim @if($claimedByUser) oleh <strong>{{ $claimedByUser->name }}</strong>@endif pada {{ isset($claimInfo['claimed_at']) ? \Carbon\Carbon::parse($claimInfo['claimed_at'])->translatedFormat('d F Y H:i') : '-' }}.
                                                    </div>
                                                @endif
                                                @if($claimInfo['allowed'] ?? false)
                                                    <form method="POST" action="{{ route('poa.claim', $poa) }}" class="grid grid-cols-1 md:grid-cols-2 gap-3" x-data='{
                                                        employees: @json(($employeeMap ?? collect())->map(fn($e)=>['id'=>(int)$e->id,'nama'=>$e->nama,'pangkat'=>$e->pangkat_golongan])->values()),
                                                        selected: @json((array) (($claimInfo['participant_ids'] ?? []) ?: $poa->participants->pluck("employee_id")->map(fn($i)=>(int)$i)->values()->all())),
                                                        borrowed: @json((array) ($meta['borrowed_map'] ?? [])),
                                                        getName(id){ const e=this.employees.find(x=>x.id===parseInt(id)); return e? (e.nama + (e.pangkat?` (${e.pangkat})`:'')) : ('ID '+id); }
                                                    }'>
                                                        @csrf
                                                        <input type="hidden" name="month" value="{{ $m }}">
                                                        <input type="hidden" name="jumlah_desa_darat" value="{{ $jumlahDaratForm }}">
                                                        <input type="hidden" name="jumlah_desa_seberang" value="{{ $jumlahSeberangForm }}">
                                                        @php
                                                            // Formatter: urutkan sesuai preferensi config dan format dengan prefix "Desa" dan konjungsi "dan".
                                                            $formatDesa = function(array $baseNames, array $pref) {
                                                                // Normalize: hilangkan prefiks "Desa " pada input, tapi simpan display dengan "Desa ".
                                                                $norm = [];
                                                                foreach ($baseNames as $i => $nm) {
                                                                    $clean = trim(preg_replace('/^\s*Desa\s+/iu', '', (string) $nm));
                                                                    if ($clean !== '') $norm[] = ['name' => $clean, 'idx' => $i];
                                                                }
                                                                // Urutkan berdasar posisi pada preferensi; yang tak ada di pref → di belakang sesuai urutan awal
                                                                $pos = [];
                                                                foreach ($pref as $k => $v) { $pos[trim($v)] = $k; }
                                                                usort($norm, function($a, $b) use ($pos) {
                                                                    $pa = $pos[$a['name']] ?? 9999;
                                                                    $pb = $pos[$b['name']] ?? 9999;
                                                                    if ($pa === $pb) return $a['idx'] <=> $b['idx'];
                                                                    return $pa <=> $pb;
                                                                });
                                                                $ordered = array_map(fn($x) => $x['name'], $norm);
                                                                $n = count($ordered);
                                                                if ($n === 0) return '';
                                                                if ($n === 1) return 'Desa ' . $ordered[0];
                                                                if ($n === 2) return 'Desa ' . $ordered[0] . ' dan Desa ' . $ordered[1];
                                                                $head = implode(', ', array_map(fn($n) => 'Desa ' . $n, array_slice($ordered, 0, -1)));
                                                                $tail = 'Desa ' . end($ordered);
                                                                return $head . ' dan ' . $tail;
                                                            };

                                                            // Kumpulkan nama desa dari pilihan admin (dvIds/svIds); jika kosong, ambil dari config preferensi sesuai kuota target.
                                                            $prefDarat = (array) config('bok.villages.default_names.DARAT', []);
                                                            $prefSeberang = (array) config('bok.villages.default_names.SEBERANG', []);
                                                            $selDarat = collect($dvIds)->map(fn($id) => $villages->firstWhere('id', $id)?->nama)->filter()->values()->all();
                                                            $selSeberang = collect($svIds)->map(fn($id) => $villages->firstWhere('id', $id)?->nama)->filter()->values()->all();

                                                            if (empty($selDarat) && $jumlahDaratForm > 0) {
                                                                $selDarat = array_slice($prefDarat, 0, max(0, (int)$jumlahDaratForm));
                                                            }
                                                            if (empty($selSeberang) && $jumlahSeberangForm > 0) {
                                                                $selSeberang = array_slice($prefSeberang, 0, max(0, (int)$jumlahSeberangForm));
                                                            }

                                                            $desaDaratDefaultInput = $formatDesa($selDarat, $prefDarat);
                                                            $desaSeberangDefaultInput = $formatDesa($selSeberang, $prefSeberang);
                                                        @endphp
                                                        @if($jumlahDaratForm > 0 || empty($claimInfo['no_surat_sppt']))
                                                            @if(!empty($claimInfo['no_surat_sppt']))
                                                                <div>
                                                                    <label class="block text-xs text-gray-600 mb-1">Nomor Surat SPPT</label>
                                                                    <div class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700">{{ $claimInfo['no_surat_sppt'] }}</div>
                                                                    <input type="hidden" name="no_surat_sppt" value="{{ $claimInfo['no_surat_sppt'] }}">
                                                                </div>
                                                            @else
                                                                <div>
                                                                    <label class="block text-xs text-gray-600 mb-1">Nomor Surat SPPT</label>
                                                                    <input name="no_surat_sppt" value="{{ old('no_surat_sppt') }}" class="w-full rounded-lg border-gray-300" placeholder="cth: 003">
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <label class="block text-xs text-gray-600 mb-1">Tanggal Surat SPPT</label>
                                                                <div class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700">{{ $claimInfo['tanggal_surat_sppt'] ?? '-' }}</div>
                                                            </div>
                                                        @endif
                                                        @if($jumlahSeberangForm > 0 || empty($claimInfo['no_surat_sppd']))
                                                            @if(!empty($claimInfo['no_surat_sppd']))
                                                                <div>
                                                                    <label class="block text-xs text-gray-600 mb-1">Nomor Surat SPPD</label>
                                                                    <div class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700">{{ $claimInfo['no_surat_sppd'] }}</div>
                                                                    <input type="hidden" name="no_surat_sppd" value="{{ $claimInfo['no_surat_sppd'] }}">
                                                                </div>
                                                            @else
                                                                <div>
                                                                    <label class="block text-xs text-gray-600 mb-1">Nomor Surat SPPD</label>
                                                                    <input name="no_surat_sppd" value="{{ old('no_surat_sppd') }}" class="w-full rounded-lg border-gray-300" placeholder="cth: 003">
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <label class="block text-xs text-gray-600 mb-1">Tanggal Surat SPPD</label>
                                                                <div class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700">{{ $claimInfo['tanggal_surat_sppd'] ?? '-' }}</div>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <label class="block text-xs text-gray-600 mb-1">Nama Desa Darat (opsional)</label>
                                                            <input name="desa_tujuan_darat" class="w-full rounded-lg border-gray-300" placeholder="cth: Desa A, Desa B" value="{{ old('desa_tujuan_darat', $desaDaratDefaultInput) }}">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs text-gray-600 mb-1">Nama Desa Seberang (opsional)</label>
                                                            <input name="desa_tujuan_seberang" class="w-full rounded-lg border-gray-300" placeholder="cth: Desa C, Desa D" value="{{ old('desa_tujuan_seberang', $desaSeberangDefaultInput) }}">
                                                        </div>
                                                        <div class="md:col-span-2">
                                                            <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg">Buat SPPT &amp; SPPD</button>
                                                            <p class="text-xs text-gray-500 mt-2">Data surat akan digunakan untuk otomatis mengisi SPPT dan SPPD.</p>
                                                        </div>
                                                        <div class="md:col-span-2 mt-2 p-3 border border-gray-200 rounded-lg bg-gray-50">
                                                            <div class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                                                                <i class="fas fa-user-tag text-indigo-600 mr-2"></i>Pinjam Nama per Peserta (opsional)
                                                            </div>
                                                            <template x-if="(selected||[]).length===0">
                                                                <div class="text-xs text-gray-500">Tidak ada peserta bulan.</div>
                                                            </template>
                                                            <div class="space-y-2" x-show="(selected||[]).length>0">
                                                                <template x-for="id in selected" :key="'claim-borrow-'+id">
                                                                    <div class="flex items-center gap-3">
                                                                        <div class="w-1/3 text-sm text-gray-700 truncate" x-text="getName(id)"></div>
                                                                        <div class="flex-1">
                                                                            <select class="w-full rounded-lg border-gray-300 text-sm" x-model.number="borrowed[id]">
                                                                                <option :value="null">Tidak Pinjam</option>
                                                                                <template x-for="e in employees" :key="'optc-'+e.id">
                                                                                    <option :value="e.id" x-text="e.nama"></option>
                                                                                </template>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </template>
                                                                <input type="hidden" name="borrowed_map" :value="JSON.stringify(borrowed || {})">
                                                                <p class="text-[11px] text-gray-500 mt-1">Nama dokumen SPPT/SPPD akan memakai ASN terpilih, saldo tetap masuk ke pelaksana.</p>
                                                            </div>
                                                        </div>
                                                    </form>
                                                @endif
                                            </div>
                                        @endif
                                        <!-- Optional Items -->
                                        @if(isset($optionalItemsDetails) && count($optionalItemsDetails))
                                            <div class="mt-6">
                                                <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                                                    <i class="fas fa-clipboard-list mr-2 text-indigo-600"></i>
                                                    Rincian Item Opsional RAB
                                                </h4>
                                                <div class="bg-gray-50 rounded-xl p-4">
                                                    <div class="space-y-3">
                                                        @php $monthProgress = $progressMonths[$m]['items'] ?? []; @endphp
                                                        @foreach($optionalItemsDetails as $ri)
                                                            @php 
                                                                $pi = $monthProgress[$ri['id']] ?? null; 
                                                                $amount = (float) ($pi['absorbed_amount'] ?? 0);
                                                                if ($amount <= 0) {
                                                                    $amount = (float) ($optionalEstimatesByMonth[$m][$ri['id']] ?? 0);
                                                                }
                                                                $typeLower = strtolower((string) ($ri['type'] ?? ''));
                                                                $labelLower = strtolower((string) ($ri['label'] ?? ''));
                                                                $isTaxedSnack = in_array($typeLower, ['snack','konsumsi'], true) || str_contains($labelLower, 'snack') || str_contains($labelLower, 'konsumsi');
                                                            @endphp
                                                            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:border-indigo-300 transition-colors duration-200">
                                                                <div class="flex items-center justify-between">
                                                                    <div>
                                                                        <div class="text-sm font-semibold text-gray-800">
                                                                            {{ $ri['label'] }}
                                                                            <span class="ml-2 text-indigo-700">- Rp {{ number_format($amount, 0, ',', '.') }}</span>
                                                                        </div>
                                                                        @if($isTaxedSnack)
                                                                            <div class="mt-1 text-[11px] text-amber-700 flex items-center">
                                                                                <i class="fas fa-receipt mr-1"></i>
                                                                                Item konsumsi/snack dikenakan potongan pajak.
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    @if(!empty($pi['completed']))
                                                                        <span class="inline-flex items-center text-green-700 bg-green-100 px-3 py-1 rounded-full text-sm font-medium">
                                                                            <i class="fas fa-check mr-1"></i>
                                                                            Selesai
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                
                                                                @if(auth()->check() && auth()->user()->isSuperAdmin())
                                                                <div class="mt-3 pt-3 border-t border-gray-100">
                                                                    <form method="POST" action="{{ route('poa.item_progress.update', $poa) }}" class="flex items-center gap-3">
                                                                        @csrf
                                                                        <input type="hidden" name="rab_item_id" value="{{ $ri['id'] }}">
                                                                        <input type="hidden" name="month" value="{{ $m }}">
                                                                        <label class="inline-flex items-center text-sm text-gray-600">
                                                                            <input type="hidden" name="completed" value="0">
                                                                            <input type="checkbox" name="completed" value="1" {{ !empty($pi['completed']) ? 'checked' : '' }} class="mr-2 rounded">
                                                                            Tandai Selesai
                                                                        </label>
                                                                        <input type="number" step="0.01" name="absorbed_amount" value="{{ $pi['absorbed_amount'] ?? 0 }}" 
                                                                               class="w-32 rounded-lg border-gray-300 text-sm" placeholder="Terserap">
                                                                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                                                            Simpan
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if(($real ?? 0) > 0)
                                            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4">
                                                <p class="text-sm text-blue-800">
                                                    <i class="fas fa-info-circle mr-2"></i>
                                                    <strong>Catatan:</strong> Transport dan uang harian otomatis terpenuhi saat LPJ dibuat
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Modal Footer with Actions -->
                                    @if(auth()->check() && auth()->user()->isSuperAdmin())
                                    <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <button @click="open=false" class="text-gray-600 hover:text-gray-800 px-4 py-2 font-medium transition-colors duration-200">
                                                Tutup
                                            </button>
                                            <div class="flex gap-2">
                                                @php 
                                                    $realAmt = isset($executedSums) ? (float) ($executedSums[$m] ?? 0) : 0; 
                                                    $progAmt = isset($progressSums) ? (float) ($progressSums[$m] ?? 0) : 0; 
                                                    $effectiveAmt = $realAmt + $progAmt; 
                                                @endphp
                                                @if(($amt ?? 0) > 0 && $effectiveAmt < $amt)
                                                <form method="POST" action="{{ route('poa.schedule.carryover', $poa) }}">
                                                    @csrf
                                                    <input type="hidden" name="month" value="{{ $m }}">
                                                    <input type="hidden" name="realized" value="{{ $realAmt ?? 0 }}">
                                                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                                                        <i class="fas fa-forward mr-2"></i>
                                                        Salin Sisa ke Bulan Berikutnya
                                                    </button>
                                                </form>
                                            @endif
                                                <form method="POST" action="{{ route('poa.schedule.toggle_claim_lock', $poa) }}" class="js-ajax-form" data-kind="claim_lock">
                                                    @csrf
                                                    <input type="hidden" name="month" value="{{ $m }}">
                                                    <input type="hidden" name="locked" value="{{ ($claimInfo['locked'] ?? false) ? 0 : 1 }}">
                                                    <button class="{{ ($claimInfo['locked'] ?? false) ? 'bg-red-600 hover:bg-red-700' : 'bg-red-500 hover:bg-red-600' }} text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                                                        <i class="fas fa-lock{{ ($claimInfo['locked'] ?? false) ? '-open' : '' }} mr-2"></i>
                                                        {{ ($claimInfo['locked'] ?? false) ? 'Buka Klaim' : 'Kunci Klaim' }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Quick Action Buttons (visible when not in popup) -->
                            <div class="flex items-center justify-between mt-2">
                                <div class="flex gap-2">
                                    @if($claimInfo['assigned'] ?? false)
                                        <span class="inline-flex items-center px-1 py-1 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-medium">
                                            <i class="fas fa-user-check mr-1"></i>Anda Ditugaskan
                                        </span>
                                    @endif
                                    @if($claimInfo['claimed_visible'] ?? false)
                                        <span class="inline-flex items-center px-1 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-medium">
                                            <i class="fas fa-clipboard-check mr-1"></i>Sudah Diklaim
                                        </span>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    @if(auth()->check() && auth()->user()->isSuperAdmin())
                                        @php 
                                            $realAmt = isset($executedSums) ? (float) ($executedSums[$m] ?? 0) : 0; 
                                            $progAmt = isset($progressSums) ? (float) ($progressSums[$m] ?? 0) : 0; 
                                            $effectiveAmt = $realAmt + $progAmt; 
                                        @endphp
                                        @if(($amt ?? 0) > 0 && $effectiveAmt < $amt)
                                        <form method="POST" action="{{ route('poa.schedule.carryover', $poa) }}">
                                            @csrf
                                            <input type="hidden" name="month" value="{{ $m }}">
                                            <input type="hidden" name="realized" value="{{ $realAmt ?? 0 }}">
                                            <button class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-2 py-1 rounded transition-colors duration-200">Salin Sisa →</button>
                                        </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Peserta/Tim Pelaksana</h3>
                @if(($participantLimit ?? 0) > 0)
                    <p class="text-xs text-gray-600 mb-3">Batas peserta sesuai RAB: <strong>{{ (int) $participantLimit }}</strong> pegawai.</p>
                @endif
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pegawai</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sebagai</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pinjam Nama</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($poa->participants as $p)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $p->employee?->nama }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $p->role ?: '-' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $p->borrowedEmployee?->nama ?: '-' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $p->note ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada peserta</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
</div>
    </div>
    
    <script>
        window.villagePicker = function (config) {
            config = config || {};
            const initialDarat = Array.isArray(config.initialDarat) ? config.initialDarat : [];
            const initialSeberang = Array.isArray(config.initialSeberang) ? config.initialSeberang : [];
            const daratDates = config.daratDates ? { ...config.daratDates } : {};
            const seberangDates = config.seberangDates ? { ...config.seberangDates } : {};

            return {
                allVillages: Array.isArray(config.villages) ? config.villages : [],
                selected: {
                    darat: Array.from(new Set(initialDarat.map(Number))),
                    seberang: Array.from(new Set(initialSeberang.map(Number))),
                },
                dates: {
                    darat: daratDates,
                    seberang: seberangDates,
                },
                startDate: config.startDate || null,
                endDate: config.endDate || null,
                init() {
                    this.ensureDateKeys('darat');
                    this.ensureDateKeys('seberang');
                    this.updateHidden('darat');
                    this.updateHidden('seberang');
                    this.$watch('selected.darat', () => {
                        this.ensureDateKeys('darat');
                        this.updateHidden('darat');
                    });
                    this.$watch('selected.seberang', () => {
                        this.ensureDateKeys('seberang');
                        this.updateHidden('seberang');
                    });
                },
                filterVillages(type) {
                    const akses = type === 'darat' ? 'DARAT' : 'SEBERANG';
                    const filtered = this.allVillages.filter(v => (v.akses || '').toUpperCase() === akses);
                    return filtered.length ? filtered : this.allVillages;
                },
                labelFor(id) {
                    const found = this.allVillages.find(v => v.id === id);
                    return found ? found.nama : 'Desa';
                },
                ensureDateKeys(type) {
                    const selected = this.selected[type];
                    Object.keys(this.dates[type]).forEach(key => {
                        const numeric = Number(key);
                        if (!selected.includes(numeric)) {
                            delete this.dates[type][key];
                        }
                    });
                    selected.forEach(id => {
                        if (this.dates[type][id] === undefined) {
                            this.dates[type][id] = '';
                        }
                    });
                },
                updateHidden(type) {
                    const ref = type === 'darat' ? this.$refs.daratIds : this.$refs.seberangIds;
                    if (ref) {
                        ref.value = this.selected[type].join(',');
                    }
                },
                autoFill(type, target) {
                    const desired = parseInt(target, 10) || 0;
                    if (desired <= 0) return;
                    const akses = type === 'darat' ? 'DARAT' : 'SEBERANG';
                    let candidates = this.allVillages.filter(v => (v.akses || '').toUpperCase() === akses);
                    if (candidates.length < desired) {
                        candidates = this.allVillages;
                    }
                    const ids = candidates.slice(0, desired).map(v => v.id);
                    this.selected[type] = Array.from(new Set(ids.map(Number)));
                    this.ensureDateKeys(type);
                    this.updateHidden(type);
                },
                clear(type) {
                    this.selected[type] = [];
                    this.ensureDateKeys(type);
                    this.updateHidden(type);
                }
            };
        };

        // Employee dropdown picker moved inline in the form using Alpine x-data

        // Simple month calendar + holiday & date pickers for kegiatan/surat
        window.calendarManager = function(cfg) {
            cfg = cfg || {};
            const year = parseInt(cfg.year, 10) || new Date().getFullYear();
            const month = parseInt(cfg.month, 10) || (new Date().getMonth()+1);
            const monthName = cfg.monthName || '';
            const daysInMonth = new Date(year, month, 0).getDate();
            const initialHolidayDays = Array.isArray(cfg.initialHolidayDays) ? cfg.initialHolidayDays.map(d => parseInt(d,10)).filter(Boolean) : [];
            const holidayYmdCurrent = Array.isArray(cfg.holidayYmdCurrent) ? cfg.holidayYmdCurrent : [];
            const holidayYmdPrev = Array.isArray(cfg.holidayYmdPrev) ? cfg.holidayYmdPrev : [];
            const limitSppt = parseInt(cfg.limitSppt || 0, 10) || 0;
            const limitSppd = parseInt(cfg.limitSppd || 0, 10) || 0;

            return {
                year,
                month,
                monthName,
                days: Array.from({length: daysInMonth}, (_,i) => i+1),
                holidaySet: new Set(initialHolidayDays),
                selected: {
                    sppt: new Set(),
                    sppd: new Set(),
                },
                mode: 'kegiatan_sppt',
                surat: {
                    spptText: '',
                    sppdText: '',
                    spptAuto: true,
                    sppdAuto: true,
                },
                weekdayNames: ['Min','Sen','Sel','Rab','Kam','Jum','Sab'],
                warnMessage: '',
                get firstDayIndex() {
                    // JS Date months are 0-based
                    return new Date(this.year, this.month - 1, 1).getDay(); // 0=Sun..6=Sat
                },
                get cells() {
                    const blanks = Array.from({length: this.firstDayIndex}, (_,i) => ({ key: 'b'+i, day: null }));
                    const days = this.days.map(d => ({ key: 'd'+d, day: d }));
                    return [...blanks, ...days];
                },
                isHoliday(d) { return this.holidaySet.has(parseInt(d,10)); },
                isHolidayYmd(ymd) {
                    if (!ymd) return false;
                    if (holidayYmdCurrent.includes(ymd) || holidayYmdPrev.includes(ymd)) return true;
                    // Also consider unsaved toggles for current month
                    const m = parseInt(ymd.slice(5,7), 10);
                    const y = parseInt(ymd.slice(0,4), 10);
                    const d = parseInt(ymd.slice(8,10), 10);
                    if (y === this.year && m === this.month) {
                        return this.holidaySet.has(d);
                    }
                    return false;
                },
                toggleHoliday(d) {
                    d = parseInt(d,10);
                    if (this.holidaySet.has(d)) this.holidaySet.delete(d); else this.holidaySet.add(d);
                },
                get holidayCsv() {
                    // Emit as YYYY-MM-DD for each selected holiday
                    const list = Array.from(this.holidaySet).sort((a,b)=>a-b).map(d => `${String(this.year).padStart(4,'0')}-${String(this.month).padStart(2,'0')}-${String(d).padStart(2,'0')}`);
                    return list.join(',');
                },
                toggleSelected(type, d) {
                    d = parseInt(d,10);
                    const set = this.selected[type];
                    if (!set) return;
                    const limit = (type === 'sppt') ? limitSppt : (type === 'sppd' ? limitSppd : 0);
                    // prevent overlap SPPT vs SPPD
                    if (type === 'sppt' && this.selected.sppd.has(d)) {
                        this.notify('Tanggal ini sudah dipakai SPPD. Pilih tanggal lain.');
                        return;
                    }
                    if (type === 'sppd' && this.selected.sppt.has(d)) {
                        this.notify('Tanggal ini sudah dipakai SPPT. Pilih tanggal lain.');
                        return;
                    }
                    if (set.has(d)) {
                        set.delete(d);
                    } else {
                        if (limit > 0 && set.size >= limit) {
                            this.notify(`Maksimal ${limit} tanggal untuk ${type.toUpperCase()} sesuai RAB.`);
                            return;
                        }
                        set.add(d);
                    }
                    this.updateKegiatanInput(type);
                    this.recomputeSuratAuto(type);
                },
                clearSelected(type) {
                    const set = this.selected[type];
                    if (set) set.clear();
                    this.updateKegiatanInput(type);
                },
                notify(msg) {
                    this.warnMessage = msg || '';
                    if (!this.warnMessage) return;
                    setTimeout(() => { this.warnMessage = ''; }, 3000);
                },
                updateKegiatanInput(type) {
                    const inputEl = type === 'sppt' ? this.$refs.tglKegiatanSppt : this.$refs.tglKegiatanSppd;
                    if (!inputEl) return;
                    const set = this.selected[type];
                    const arr = Array.from(set).sort((a,b)=>a-b);
                    inputEl.value = this.formatKegiatan(arr);
                },
                formatKegiatan(days) {
                    if (!days || days.length === 0) return '';
                    const monthName = this.monthName || '';
                    const year = this.year;
                    if (days.length === 1) {
                        return `${days[0]} ${monthName} ${year}`;
                    }
                    if (days.length === 2) {
                        return `${days[0]} dan ${days[1]} ${monthName} ${year}`;
                    }
                    // For 3+ dates, only use range notation if they are consecutive.
                    const isConsecutive = days.every((d, i) => i === 0 || d === days[i-1] + 1);
                    if (isConsecutive) {
                        const start = days[0];
                        const end = days[days.length-1];
                        return `${start} s/d ${end} ${monthName} ${year}`;
                    }
                    // Otherwise, output explicit list so backend counts diskrit days correctly
                    // e.g., "13, 14 dan 18 Mei 2025"
                    const head = days.slice(0, -1).join(', ');
                    const tail = days[days.length - 1];
                    return `${head} dan ${tail} ${monthName} ${year}`;
                },
                pickSurat(d, which) {
                    d = parseInt(d,10);
                    if (this.isHoliday(d)) return; // block
                    const inputEl = which === 'sppt' ? this.$refs.tglSuratSppt : this.$refs.tglSuratSppd;
                    if (!inputEl) return;
                    inputEl.value = `${d} ${this.monthName} ${this.year}`;
                    if (which === 'sppt') { this.surat.spptText = inputEl.value; this.surat.spptAuto = false; }
                    if (which === 'sppd') { this.surat.sppdText = inputEl.value; this.surat.sppdAuto = false; }
                },
                // Single calendar behavior with persistent SPPT/SPPD coloring
                cellClass(day) {
                    if (day === null) return 'bg-transparent';
                    const spptSel = this.selected.sppt.has(day);
                    const sppdSel = this.selected.sppd.has(day);
                    const isHol = this.isHoliday(day);
                    let base = 'border rounded-md py-2 ';
                    // Determine base style by mode/holiday
                    let style = 'bg-white hover:bg-gray-50 text-gray-800 border-gray-200 cursor-pointer';
                    if (this.mode === 'holiday') {
                        style = (isHol ? 'bg-red-100 border-red-300 text-red-700' : 'bg-white hover:bg-gray-50 text-gray-800 border-gray-200') + ' cursor-pointer';
                    } else if (this.mode === 'surat_sppt' || this.mode === 'surat_sppd') {
                        if (isHol) {
                            style = 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed';
                        }
                    }
                    // Overlay selection colors persistently
                    if (spptSel && sppdSel) {
                        style = 'bg-amber-100 border-amber-300 text-amber-800 cursor-pointer';
                    } else if (spptSel) {
                        style = 'bg-indigo-100 border-indigo-300 text-indigo-700 cursor-pointer';
                    } else if (sppdSel) {
                        style = 'bg-green-100 border-green-300 text-green-700 cursor-pointer';
                    }
                    // Ensure holiday stays visible in non-holiday modes by adding a subtle red ring
                    if (isHol && this.mode !== 'holiday') {
                        style += ' ring-1 ring-red-300';
                    }
                    return style;
                },
                onCellClick(day) {
                    if (day === null) return;
                    if (this.mode === 'holiday') return this.toggleHoliday(day);
                    if (this.mode === 'kegiatan_sppt') return this.toggleSelected('sppt', day);
                    if (this.mode === 'kegiatan_sppd') return this.toggleSelected('sppd', day);
                    if (this.mode === 'surat_sppt') return this.pickSurat(day, 'sppt');
                    if (this.mode === 'surat_sppd') return this.pickSurat(day, 'sppd');
                },
                get labelMode() {
                    return {
                        holiday: 'Tanggal Merah',
                        kegiatan_sppt: 'Kegiatan SPPT',
                        kegiatan_sppd: 'Kegiatan SPPD',
                        surat_sppt: 'Surat Keluar SPPT',
                        surat_sppd: 'Surat Keluar SPPD',
                    }[this.mode] || '-';
                },
                recomputeSuratAuto(type) {
                    const set = this.selected[type];
                    if (!set || set.size === 0) return;
                    const minDay = Math.min(...Array.from(set));
                    const startDate = new Date(this.year, this.month - 1, minDay);
                    const prev = this.prevWorkday(startDate);
                    const text = this.formatSurat(prev);
                    if (type === 'sppt') {
                        const input = this.$refs.tglSuratSppt;
                        if (this.surat.spptAuto || !(input && input.value)) {
                            if (input) input.value = text;
                            this.surat.spptText = text;
                            this.surat.spptAuto = true;
                        }
                    } else {
                        const input = this.$refs.tglSuratSppd;
                        if (this.surat.sppdAuto || !(input && input.value)) {
                            if (input) input.value = text;
                            this.surat.sppdText = text;
                            this.surat.sppdAuto = true;
                        }
                    }
                },
                prevWorkday(date) {
                    let d = new Date(date.getFullYear(), date.getMonth(), date.getDate());
                    d.setDate(d.getDate() - 1);
                    for (let i = 0; i < 400; i++) {
                        const y = d.getFullYear();
                        const m = d.getMonth() + 1;
                        const day = d.getDate();
                        const ymd = `${String(y).padStart(4,'0')}-${String(m).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
                        if (!this.isHolidayYmd(ymd)) {
                            return { y, m, day };
                        }
                        d.setDate(d.getDate() - 1);
                    }
                    return { y: d.getFullYear(), m: d.getMonth()+1, day: d.getDate() };
                },
                formatSurat(obj) {
                    if (!obj) return '';
                    const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                    return `${obj.day} ${months[obj.m-1]} ${obj.y}`;
                },
                formatKegiatanDisplay(set) {
                    const arr = Array.from(set || new Set()).sort((a,b)=>a-b);
                    return this.formatKegiatan(arr) || '-';
                }
            };
        }
    </script>

    <!-- Additional CSS for smooth animations -->
    <style>
        /* Smooth scroll for modal content */
        .overflow-y-auto {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f7fafc;
        }
        
        .overflow-y-auto::-webkit-scrollbar {
            width: 8px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f7fafc;
            border-radius: 4px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 4px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }
        
        /* Pulse animation enhancement */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        /* Enhanced hover effects */
        .hover\:shadow-lg {
            transition: box-shadow 0.3s ease;
        }
        
        /* Modal backdrop blur support */
        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }
</style>
    @php
        $reviewTbId = session('tiba_berangkat_review_id');
        $reviewTb = null;
        if ($reviewTbId) {
            $reviewTb = \App\Models\TibaBerangkat::with(['details.pejabatTtd'])
                ->where('id', $reviewTbId)
                ->where('created_by', auth()->id())
                ->first();
        }
        $reviewPairIds = session('download_pair_ids');
        $suggestTbFrom = session('suggest_tb_from');
    @endphp

    @if ($reviewTb)
    <!-- Auto Tiba Berangkat Review Modal (from POA Claim) -->
    <div id="autoTbModal" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl px-6 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-route text-emerald-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tiba Berangkat dibuat otomatis</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Kami menyusun urutan desa berdasarkan tanggal kunjungan dari SPPT dan/atau SPPD. Anda dapat mengubah tanggal langsung di sini sebelum download.</p>
                            <form id="autoTbForm" data-action="{{ route('tiba-berangkats.quick_update', $reviewTb) }}" class="mt-3 max-h-64 overflow-y-auto border rounded-lg p-3 bg-gray-50">
                                @csrf
                                <div class="grid grid-cols-1 gap-3">
                                    <div class="flex items-center gap-3">
                                        <label class="text-sm font-medium text-gray-700 w-32">No. Surat</label>
                                        <input type="text" name="no_surat" value="{{ $reviewTb->no_surat }}" class="flex-1 border rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500" />
                                    </div>
                                    @foreach ($reviewTb->details->sortBy('tanggal_kunjungan') as $d)
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm text-gray-700 w-32 font-medium">{{ $d->pejabatTtd->desa }}</span>
                                            <input type="date" class="tb-date-input border rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500" data-detail-id="{{ $d->id }}" value="{{ $d->tanggal_kunjungan->format('Y-m-d') }}" />
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-2">
                    @if (is_array($reviewPairIds) && count($reviewPairIds) >= 2)
                        <button id="saveAndDownloadTripleBtn" data-tbid="{{ $reviewTb->id }}" data-lpjids="{{ implode(',', $reviewPairIds) }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:w-auto sm:text-sm">Simpan & Download 3 Dokumen</button>
                    @else
                        <button id="saveAndDownloadTripleBtn" data-tbid="{{ $reviewTb->id }}" data-lpjids="" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:w-auto sm:text-sm">Simpan & Download TB</button>
                    @endif
                    <a href="{{ route('tiba-berangkats.edit', $reviewTb) }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Tinjau & Edit</a>
                    <button id="dismissAutoTb" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($suggestTbFrom && !$reviewTb)
    <!-- Suggest Create Tiba Berangkat Modal (from POA Claim) -->
    <div id="suggestTbModal" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="tb-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl px-6 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-route text-emerald-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="tb-modal-title">Buat Tiba Berangkat sekarang?</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Kami akan memprefill daftar desa dari LPJ yang barusan dibuat. Tanggal bisa Anda atur nanti.</p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-2">
                    <a id="goCreateTbBtn" href="{{ url('/tiba-berangkats/auto-from-lpj') }}/{{ $suggestTbFrom }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">Ya, buat Tiba Berangkat</a>
                    <button id="dismissSuggestTb" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Nanti saja</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        // Helper to trigger file downloads without leaving the page
        function triggerDownload(url) {
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = url;
            document.body.appendChild(iframe);
            setTimeout(() => { try { document.body.removeChild(iframe); } catch (e) {} }, 60000);
        }

        // Wire up TB modals if present
        (function(){
            const autoTbModal = document.getElementById('autoTbModal');
            const dismissAutoTb = document.getElementById('dismissAutoTb');
            if (autoTbModal && dismissAutoTb) {
                dismissAutoTb.addEventListener('click', function() { autoTbModal.style.display = 'none'; });
            }

            const saveAndDownloadTripleBtn = document.getElementById('saveAndDownloadTripleBtn');
            if (saveAndDownloadTripleBtn) {
                saveAndDownloadTripleBtn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const tbid = this.dataset.tbid;
                    const ids = (this.dataset.lpjids || '').split(',').map(s => s.trim()).filter(Boolean);
                    const formEl = document.getElementById('autoTbForm');
                    if (!formEl) return;
                    const action = formEl.dataset.action;

                    const details = Array.from(document.querySelectorAll('.tb-date-input')).map(input => ({
                        id: parseInt(input.dataset.detailId, 10),
                        tanggal_kunjungan: input.value
                    }));
                    const noSuratInput = formEl.querySelector('input[name="no_surat"]');
                    const payload = { no_surat: noSuratInput ? noSuratInput.value : '', details };

                    try {
                        const res = await fetch(action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(payload)
                        });
                        if (!res.ok) throw new Error('Gagal menyimpan perubahan');
                        // Download LPJ pair (if any) then TB
                        ids.forEach((id, idx) => setTimeout(() => triggerDownload(`/lpj/${id}/download`), idx * 400));
                        if (tbid) setTimeout(() => triggerDownload(`/tiba-berangkats/${tbid}/download`), ids.length * 450);
                        if (autoTbModal) autoTbModal.style.display = 'none';
                    } catch (err) {
                        alert(err.message || 'Terjadi kesalahan saat menyimpan data.');
                    }
                });
            }

            const suggestTbModal = document.getElementById('suggestTbModal');
            const dismissSuggestTb = document.getElementById('dismissSuggestTb');
            if (suggestTbModal && dismissSuggestTb) {
                dismissSuggestTb.addEventListener('click', function(){ suggestTbModal.style.display = 'none'; });
            }
        })();
    </script>
    <script>
        (function(){
            const forms = document.querySelectorAll('form.js-ajax-form');
            if (!forms.length) return;

            function updateRingClasses(card, real, marked) {
                // Remove possible ring classes first
                card.classList.remove('ring-2','ring-indigo-400','ring-amber-400');
                if (real) {
                    card.classList.add('ring-2','ring-indigo-400');
                } else if (marked) {
                    card.classList.add('ring-2','ring-amber-400');
                }
            }

            function renderStatusBadge(card) {
                const real = card.dataset.real === '1';
                const marked = card.dataset.marked === '1';
                const locked = card.dataset.locked === '1';
                const container = card.querySelector('[data-status-badge]');
                if (!container) return;
                if (real) {
                    container.innerHTML = '<div class="text-indigo-600 text-xs inline-flex items-center animate-pulse"><i class="fas fa-check-circle mr-1"></i>Jalan</div>';
                } else if (marked) {
                    container.innerHTML = '<div class="text-amber-600 text-xs inline-flex items-center animate-pulse"><i class="fas fa-clock mr-1"></i>Perlu LPJ</div>';
                } else if (locked) {
                    container.innerHTML = '<div class="text-red-600 text-xs inline-flex items-center"><i class="fas fa-lock mr-1"></i>Terkunci</div>';
                } else {
                    container.innerHTML = '';
                }
                updateRingClasses(card, real, marked);
            }

            async function handleSubmit(e) {
                e.preventDefault();
                const form = e.target;
                const kind = form.dataset.kind || '';
                const card = form.closest('.poa-month-card');
                const action = form.getAttribute('action');
                const fd = new FormData(form);
                try {
                    const res = await fetch(action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: fd,
                        credentials: 'same-origin'
                    });
                    if (!res.ok) throw new Error('Gagal menyimpan perubahan');
                    const data = await res.json();
                    if (data && data.ok) {
                        if (kind === 'mark') {
                            const newMarked = !!data.marked;
                            if (card) card.dataset.marked = newMarked ? '1' : '0';
                            // Update button appearance and next value
                            const btn = form.querySelector('button');
                            if (btn) {
                                btn.textContent = newMarked ? 'ⓧ' : '✔️';
                                btn.classList.toggle('bg-amber-600', newMarked);
                                btn.classList.toggle('hover:bg-amber-700', newMarked);
                                btn.classList.toggle('bg-amber-500', !newMarked);
                                btn.classList.toggle('hover:bg-amber-600', !newMarked);
                            }
                            const input = form.querySelector('input[name="marked"]');
                            if (input) input.value = newMarked ? '0' : '1';
                            if (card) renderStatusBadge(card);
                        } else if (kind === 'claim_label') {
                            const newHidden = !!data.hidden;
                            const btn = form.querySelector('button');
                            if (btn) {
                                btn.textContent = newHidden ? '✅' : '🚫';
                                btn.classList.toggle('bg-emerald-600', newHidden);
                                btn.classList.toggle('hover:bg-emerald-700', newHidden);
                                btn.classList.toggle('bg-emerald-500', !newHidden);
                                btn.classList.toggle('hover:bg-emerald-600', !newHidden);
                            }
                            const input = form.querySelector('input[name="hidden"]');
                            if (input) input.value = newHidden ? '0' : '1';
                            // Optional: toggle a visible claimed badge elsewhere if needed.
                        } else if (kind === 'claim_lock') {
                            const newLocked = !!data.locked;
                            if (card) card.dataset.locked = newLocked ? '1' : '0';
                            const btn = form.querySelector('button');
                            if (btn) {
                                const iconEl = btn.querySelector('i');
                                if (iconEl) {
                                    iconEl.className = 'fas fa-lock' + (newLocked ? '-open' : '') + ' mr-1';
                                }
                                btn.textContent = '';
                                // Rebuild button label to include icon + text
                                const i = document.createElement('i');
                                i.className = 'fas fa-lock' + (newLocked ? '-open' : '') + ' mr-1';
                                btn.appendChild(i);
                                btn.appendChild(document.createTextNode(newLocked ? 'Buka Klaim' : 'Kunci Klaim'));
                                btn.classList.toggle('bg-red-600', newLocked);
                                btn.classList.toggle('hover:bg-red-700', newLocked);
                                btn.classList.toggle('bg-red-500', !newLocked);
                                btn.classList.toggle('hover:bg-red-600', !newLocked);
                            }
                            const input = form.querySelector('input[name="locked"]');
                            if (input) input.value = newLocked ? '0' : '1';
                            if (card) renderStatusBadge(card);
                        }
                    }
                } catch (err) {
                    alert(err.message || 'Terjadi kesalahan.');
                }
            }

            forms.forEach(f => f.addEventListener('submit', handleSubmit));
        })();
    </script>
</x-app-layout>
