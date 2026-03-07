<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white flex items-center justify-between">
                    <h1 class="text-2xl font-bold">POA (Plan of Action)</h1>
                    @if(auth()->check() && auth()->user()->isSuperAdmin())
                        <a href="{{ route('poa.create') }}" class="inline-flex items-center bg-white text-indigo-700 px-4 py-2 rounded-lg font-medium hover:bg-indigo-50 shadow-sm"><i class="fas fa-plus mr-2"></i>Buat POA</a>
                    @endif
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-6">
                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">{{ session('success') }}</div>
                    @endif
                    @if(auth()->check() && auth()->user()->isSuperAdmin())
                        <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                            <form method="POST" action="{{ route('poa.bulk_toggle_claim_lock') }}" class="flex items-center gap-2 flex-wrap">
                                @csrf
                                @php $currentYear = (int) date('Y'); @endphp
                                <div>
                                    <label class="text-xs text-gray-700">Tahun</label>
                                    <select name="year" class="mt-1 block rounded-lg border-gray-300">
                                        @for ($y = $currentYear - 1; $y <= $currentYear + 1; $y++)
                                            <option value="{{ $y }}" {{ (int) request('year', $currentYear) === (int) $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-700">Bulan</label>
                                    <select name="month" id="bulk_lock_month" class="mt-1 block rounded-lg border-gray-300" required>
                                        @php $months=[1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember']; @endphp
                                        @foreach ($months as $m => $label)
                                            <option value="{{ $m }}" {{ (int) ($selectedMonth ?? request('month', (int) date('n'))) === (int) $m ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="pt-5 flex gap-2">
                                    <button name="locked" value="1" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm"><i class="fas fa-lock mr-1"></i>Kunci Semua Kegiatan</button>
                                    <button name="locked" value="0" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg text-sm"><i class="fas fa-lock-open mr-1"></i>Buka Semua Kegiatan</button>
                                </div>
                            </form>
                        </div>
                    @endif
                    <div class="mb-4">
                        <div class="flex items-center justify-between gap-3 flex-wrap mb-3">
                            <div class="w-full overflow-x-auto scrollbar-hide -mx-1 px-1" id="monthBar">
                                <div class="flex items-center gap-2 whitespace-nowrap">
                                @php $months=[1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des']; @endphp
                                <button data-month="" class="px-2.5 py-1 rounded-full text-xs sm:text-sm sm:px-3 sm:py-1.5 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50" id="monthAllBtn">Semua</button>
                                @foreach ($months as $m => $label)
                                    <button data-month="{{ $m }}" class="relative inline-flex items-center px-2.5 py-1 rounded-full text-xs sm:text-sm sm:px-3 sm:py-1.5 border border-gray-200 text-gray-700 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-700 month-btn">
                                        <span>{{ $label }}</span>
                                        @php 
                                            $claimedCnt = (int) (($userMonthCountsClaimed[$m] ?? 0)); 
                                            $eligibleCnt = (int) (($userMonthCountsEligible[$m] ?? 0));
                                            $totalCnt = $claimedCnt + $eligibleCnt;
                                        @endphp
                                        @if($totalCnt > 0)
                                            @if($claimedCnt > 0 && $eligibleCnt > 0)
                                                <span class="ml-1 inline-flex items-center justify-center rounded-full bg-amber-500 text-white text-[10px] leading-none px-1.5 py-0.5">{{ $totalCnt }}</span>
                                            @elseif($claimedCnt > 0)
                                                <span class="ml-1 inline-flex items-center justify-center rounded-full bg-green-600 text-white text-[10px] leading-none px-1.5 py-0.5">{{ $claimedCnt }}</span>
                                            @else
                                                <span class="ml-1 inline-flex items-center justify-center rounded-full bg-red-500 text-white text-[10px] leading-none px-1.5 py-0.5">{{ $eligibleCnt }}</span>
                                            @endif
                                        @endif
                                    </button>
                                @endforeach
                                </div>
                            </div>
                            <div class="flex items-center gap-2 w-full md:w-auto">
                                @if(auth()->check() && auth()->user()->isSuperAdmin())
                                    <select id="filterKomponen" class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Semua Komponen</option>
                                        @foreach(($availableKomponen ?? []) as $k)
                                            <option value="{{ $k }}" {{ (isset($selectedKomponen) && $selectedKomponen === $k) ? 'selected' : '' }}>{{ $k }}</option>
                                        @endforeach
                                    </select>
                                    <select id="filterMenu" class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Semua Rincian Menu</option>
                                        @foreach(($availableMenus ?? []) as $rm)
                                            <option value="{{ $rm }}" {{ (isset($selectedMenu) && $selectedMenu === $rm) ? 'selected' : '' }}>{{ $rm }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <input id="poaSearch" type="text" placeholder="Ketik nama kegiatan..." class="w-full md:w-72 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                        </div>
                    </div>
                    @if(auth()->check() && auth()->user()->isSuperAdmin())
                        <div class="mb-3 flex items-center gap-3 text-[11px] text-gray-600">
                            <span class="inline-flex items-center"><span class="w-3 h-3 inline-block rounded-full bg-emerald-600 mr-1"></span>Lengkap</span>
                            <span class="inline-flex items-center"><span class="w-3 h-3 inline-block rounded-full bg-amber-400 mr-1"></span>Perlu dilengkapi</span>
                            <span class="inline-flex items-center"><span class="w-3 h-3 inline-block rounded-full bg-gray-300 mr-1"></span>Belum dijadwalkan</span>
                            <span class="inline-flex items-center"><span class="w-3 h-3 inline-block rounded-full bg-red-500 mr-1 opacity-70"></span>Terkunci</span>
                        </div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto text-sm">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-[11px] sm:text-xs font-semibold text-gray-600 uppercase tracking-wider">Kegiatan</th>
                                    <th class="px-3 py-2 sm:px-6 sm:py-3 text-right text-[11px] sm:text-xs font-semibold text-gray-600 uppercase tracking-wider">Planned Total</th>
                                    @if(auth()->check() && auth()->user()->isSuperAdmin())
                                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-right text-[11px] sm:text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="poaRows" class="bg-white divide-y divide-gray-200">
                                @include('poa._rows', ['poas' => $poas])
                            </tbody>
                        </table>
                    </div>
                    <div id="poaPagination" class="mt-4">{{ $poas->links() }}</div>
                    <!-- Global hover/tap preview elements (managed by JS) -->
                    <div id="poaHoverBackdrop" class="fixed inset-0 z-40 bg-black/30 backdrop-blur-sm hidden sm:hidden"></div>
                    <div id="poaHoverCard" class="fixed z-50 hidden" aria-hidden="true"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const search = document.getElementById('poaSearch');
    const rows = document.getElementById('poaRows');
    const pager = document.getElementById('poaPagination');
    const monthAllBtn = document.getElementById('monthAllBtn');
    const monthBtns = Array.from(document.querySelectorAll('.month-btn'));
    let activeMonth = '{{ $selectedMonth ?? '' }}';
    let t = null;
    async function run(q) {
        const url = new URL(window.location.href);
        url.searchParams.set('ajax', '1');
        url.searchParams.set('q', q || '');
        if (activeMonth && String(activeMonth).length) {
            url.searchParams.set('month', String(activeMonth));
        } else {
            url.searchParams.delete('month');
        }
        // Apply super_admin filters if present
        const kompEl = document.getElementById('filterKomponen');
        const menuEl = document.getElementById('filterMenu');
        const komp = kompEl ? kompEl.value : '';
        const menu = menuEl ? menuEl.value : '';
        if (komp) url.searchParams.set('komponen', komp); else url.searchParams.delete('komponen');
        if (menu) url.searchParams.set('rincian_menu', menu); else url.searchParams.delete('rincian_menu');
        try {
            const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await res.text();
            rows.innerHTML = html;
            if (typeof wireHoverCards === 'function') wireHoverCards();
            pager.style.display = (q || activeMonth) ? 'none' : '';
        } catch (e) { console.warn('Search failed', e); }
    }
    function debounce(fn, ms){ return function(...a){ clearTimeout(t); t = setTimeout(() => fn.apply(this, a), ms); } }
    search?.addEventListener('input', debounce((e) => run(e.target.value), 250));

    function updateMonthActive() {
        monthBtns.forEach(btn => {
            const isActive = String(btn.dataset.month) === String(activeMonth);
            btn.classList.toggle('bg-indigo-600', isActive);
            btn.classList.toggle('text-white', isActive);
            btn.classList.toggle('border-indigo-600', isActive);
            if (!isActive) {
                btn.classList.remove('bg-indigo-600','text-white','border-indigo-600');
            }
        });
        if (!activeMonth) {
            monthAllBtn.classList.add('bg-indigo-600','text-white','border-indigo-600');
        } else {
            monthAllBtn.classList.remove('bg-indigo-600','text-white','border-indigo-600');
        }
    }
    // Initialize active month from URL if present
    (function(){
        const url = new URL(window.location.href);
        const m = url.searchParams.get('month');
        if (m) activeMonth = m;
        updateMonthActive();
    })();
    monthAllBtn?.addEventListener('click', () => { activeMonth = ''; updateMonthActive(); run(search?.value || ''); });
    const bulkMonthSelect = document.getElementById('bulk_lock_month');
    function syncBulkMonth() {
        if (!bulkMonthSelect) return;
        if (String(activeMonth || '') !== '') {
            bulkMonthSelect.value = String(activeMonth);
        }
    }
    monthBtns.forEach(btn => btn.addEventListener('click', () => { activeMonth = btn.dataset.month; updateMonthActive(); syncBulkMonth(); run(search?.value || ''); }));
    // Filter dropdowns
    const filterKomponen = document.getElementById('filterKomponen');
    const filterMenu = document.getElementById('filterMenu');
    filterKomponen?.addEventListener('change', () => run(search?.value || ''));
    filterMenu?.addEventListener('change', () => run(search?.value || ''));
    // Sync initial
    syncBulkMonth();
    if (typeof wireHoverCards === 'function') wireHoverCards();
});
</script>

<style>
/* Hide scrollbar for horizontal month bar on mobile */
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
// Global hover/tap preview card logic for POA rows
function wireHoverCards() {
    const isDesktopLike = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    const card = document.getElementById('poaHoverCard');
    const backdrop = document.getElementById('poaHoverBackdrop');
    const rows = document.getElementById('poaRows');
    if (!card || !rows) return;

    // Reset card/backdrop to remove previous listeners
    const freshCard = card.cloneNode(false); card.parentNode.replaceChild(freshCard, card);
    const freshBackdrop = backdrop ? backdrop.cloneNode(false) : null; if (backdrop && freshBackdrop) backdrop.parentNode.replaceChild(freshBackdrop, backdrop);
    const hoverCard = document.getElementById('poaHoverCard');
    const overlay = document.getElementById('poaHoverBackdrop');
    hoverCard.style.pointerEvents = 'auto';

    let hideTimer = null;
    let showTimer = null;
    let activeWrapper = null;

    const hideCard = () => {
        activeWrapper = null;
        hoverCard.classList.add('hidden');
        if (overlay) overlay.classList.add('hidden');
        hoverCard.innerHTML = '';
    };

    const showCardFrom = (wrapper, mode) => {
        if (activeWrapper !== wrapper) activeWrapper = wrapper;
        const tpl = wrapper.querySelector('.js-hover-template');
        if (!tpl) return;
        hoverCard.innerHTML = '';
        const content = tpl.firstElementChild ? tpl.firstElementChild.cloneNode(true) : null;
        if (!content) return;
        if (isDesktopLike && mode !== 'mobile') {
            // Center horizontally relative to table container
            const rect = wrapper.getBoundingClientRect();
            const tableWrap = document.querySelector('.overflow-x-auto');
            const wrapRect = tableWrap ? tableWrap.getBoundingClientRect() : null;
            const centerX = wrapRect ? (wrapRect.left + wrapRect.width / 2) : (window.innerWidth / 2);
            hoverCard.style.width = '32rem';
            hoverCard.style.maxWidth = '90vw';
            hoverCard.style.left = Math.round(centerX) + 'px';
            hoverCard.style.transform = 'translateX(-50%)';
            let top = rect.bottom + 8;
            const estimatedHeight = 280;
            const maxTop = window.innerHeight - estimatedHeight - 16;
            if (top > maxTop) top = Math.max(8, rect.top - estimatedHeight - 8);
            hoverCard.style.top = top + 'px';
            hoverCard.appendChild(content);
            hoverCard.className = 'fixed z-50';
            hoverCard.classList.remove('hidden');
        } else {
            // Mobile bottom sheet with backdrop
            hoverCard.style.left = '0';
            hoverCard.style.right = '0';
            hoverCard.style.bottom = '0';
            hoverCard.style.top = 'auto';
            hoverCard.style.transform = 'none';
            content.classList.add('rounded-t-2xl');
            content.classList.remove('rounded-xl');
            // Close button
            const btn = document.createElement('button');
            btn.className = 'absolute top-2 right-3 text-gray-500 hover:text-gray-700 z-10';
            btn.innerHTML = '<i class="fas fa-times"></i>';
            btn.addEventListener('click', hideCard);
            hoverCard.appendChild(btn);
            hoverCard.appendChild(content);
            hoverCard.className = 'fixed z-50 sm:hidden';
            hoverCard.classList.remove('hidden');
            if (overlay) {
                overlay.classList.remove('hidden');
                overlay.addEventListener('click', hideCard, { once: true });
            }
        }
    };

    // Attach global listeners once
    hoverCard.addEventListener('mouseenter', () => { if (hideTimer) clearTimeout(hideTimer); });
    hoverCard.addEventListener('mouseleave', () => { hideTimer = setTimeout(hideCard, 220); });
    if (!window.__poaHoverGlobalBound) {
        window.addEventListener('scroll', hideCard, { passive: true });
        window.addEventListener('resize', hideCard);
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') hideCard(); });
        window.__poaHoverGlobalBound = true;
    }

    const wrappers = rows.querySelectorAll('.js-poa-hover');
    wrappers.forEach(wrapper => {
        const trigger = wrapper.querySelector('.js-poa-hover-trigger');
        const infoBtn = wrapper.querySelector('.js-poa-info-btn');
        if (isDesktopLike) {
            const over = () => {
                if (hideTimer) clearTimeout(hideTimer);
                if (showTimer) clearTimeout(showTimer);
                showTimer = setTimeout(() => showCardFrom(wrapper, 'desktop'), 120);
            };
            const out = () => {
                if (showTimer) clearTimeout(showTimer);
                hideTimer = setTimeout(hideCard, 350);
            };
            wrapper.addEventListener('mouseenter', over);
            wrapper.addEventListener('mouseleave', out);
            if (trigger) {
                trigger.addEventListener('mouseenter', over);
                trigger.addEventListener('mouseleave', out);
            }
        }
        if (infoBtn) {
            infoBtn.addEventListener('click', (e) => { e.preventDefault(); e.stopPropagation(); showCardFrom(wrapper, 'mobile'); });
        }
    });
}
</script>
