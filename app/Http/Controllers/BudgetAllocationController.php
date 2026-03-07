<?php

namespace App\Http\Controllers;

use App\Models\AnnualBudget;
use App\Models\BudgetAllocation;
use App\Models\ItemOpsionalClaim;
use App\Models\Poa;
use App\Models\Rab;
use App\Models\Lpj;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BudgetAllocationController extends Controller
{
    private function ensureSuperAdmin(): void
    {
        if (!auth()->user()?->isSuperAdmin()) {
            abort(403, 'Unauthorized. Super Admin access required.');
        }
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $allocations = BudgetAllocation::with(['budget', 'rab'])
            ->when($q !== '', function ($query) use ($q) {
                $query->whereHas('rab', fn($r) => $r->where('kegiatan', 'like', "%$q%"));
            })
            ->latest()
            ->paginate(10);

        if ($request->ajax() || $request->boolean('ajax')) {
            return response()->view('allocations._rows', compact('allocations'));
        }

        return view('allocations.index', compact('allocations'));
    }

    public function create()
    {
        $this->ensureSuperAdmin();
        $budgets = AnnualBudget::orderByDesc('year')->get();
        $rabs = collect();
        return view('allocations.create', compact('budgets', 'rabs'));
    }

    public function store(Request $request)
    {
        $this->ensureSuperAdmin();
        $validated = $request->validate([
            'annual_budget_id' => 'required|exists:annual_budgets,id',
            'rab_id' => [
                'required',
                'exists:rabs,id',
                Rule::unique('budget_allocations', 'rab_id')
                    ->where(fn($q) => $q->where('annual_budget_id', $request->input('annual_budget_id'))),
            ],
            'allocated_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        BudgetAllocation::create($validated);
        return redirect()->route('allocations.index')->with('success', 'Alokasi kegiatan ditambahkan.');
    }

    public function show(BudgetAllocation $allocation)
    {
        $allocation->load('budget', 'rab.items');
        // Compute realized for this allocation: sum LPJ totals in the same year with matching kegiatan
        $year = $allocation->budget->year;
        $kegiatan = $allocation->rab->kegiatan;
        $lpjs = Lpj::where('kegiatan', $kegiatan)
            ->whereYear('created_at', $year)
            ->with('participants')
            ->get();
        $realized = $lpjs->sum(fn($lpj) => $lpj->participants->sum('total_amount'));

        // Add realized item opsional from ItemOpsionalClaim records
        $rabId = $allocation->rab_id;
        $poaIds = Poa::where('rab_id', $rabId)->pluck('id');
        $realizedOptional = 0.0;
        if ($poaIds->isNotEmpty()) {
            $realizedOptional = (float) ItemOpsionalClaim::whereIn('poa_id', $poaIds)->sum('amount');
        }
        $realized += $realizedOptional;

        // Planned breakdown by RAB items
        $rab = $allocation->rab;
        $items = collect($rab->items ?? []);
        $optionalTypes = ['snack','konsumsi','penggandaan','bahan_makanan','lainnya','transport_peserta'];
        $excludeCore = ['transport_darat','transport_laut','uang_harian'];
        $isOptional = function ($it) use ($optionalTypes) {
            $type = strtolower((string) ($it->type ?? ''));
            if (in_array($type, $optionalTypes, true)) return true;
            $labelLower = mb_strtolower((string) ($it->label ?? ''), 'UTF-8');
            foreach ($optionalTypes as $t) { if (str_contains($labelLower, str_replace('_',' ', $t))) return true; }
            return false;
        };
        $isCore = function ($it) use ($excludeCore) {
            $type = strtolower((string) ($it->type ?? ''));
            return in_array($type, $excludeCore, true);
        };
        $plannedOptional = $items->filter(fn($it) => $isOptional($it) && !$isCore($it))->values();
        $plannedOptionalSum = (float) $plannedOptional->sum(fn($it) => (float) $it->subtotal);
        $plannedCore = $items->filter(fn($it) => $isCore($it))->values();
        $plannedCoreSum = (float) $plannedCore->sum(fn($it) => (float) $it->subtotal);

        $remaining = (float) $allocation->allocated_amount - (float) $realized;
        $realizedCore = max(0.0, (float) $realized - (float) $realizedOptional);
        $absorptionPercent = ((float) $allocation->allocated_amount > 0)
            ? ( (float) $realized / (float) $allocation->allocated_amount * 100.0 )
            : 0.0;
        return view('allocations.show', compact(
            'allocation', 'realized', 'remaining',
            'plannedOptional', 'plannedOptionalSum', 'plannedCoreSum', 'realizedOptional',
            'realizedCore', 'absorptionPercent'
        ));
    }

    public function edit(BudgetAllocation $allocation)
    {
        $this->ensureSuperAdmin();
        $budgets = AnnualBudget::orderByDesc('year')->get();
        $rabs = Rab::orderByDesc('created_at')->limit(100)->get();
        return view('allocations.edit', compact('allocation', 'budgets', 'rabs'));
    }

    public function update(Request $request, BudgetAllocation $allocation)
    {
        $this->ensureSuperAdmin();
        $validated = $request->validate([
            'annual_budget_id' => 'required|exists:annual_budgets,id',
            'rab_id' => [
                'required',
                'exists:rabs,id',
                Rule::unique('budget_allocations', 'rab_id')
                    ->ignore($allocation->id)
                    ->where(fn($q) => $q->where('annual_budget_id', $request->input('annual_budget_id'))),
            ],
            'allocated_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        $allocation->update($validated);
        return redirect()->route('allocations.index')->with('success', 'Alokasi kegiatan diperbarui.');
    }

    public function destroy(BudgetAllocation $allocation)
    {
        $this->ensureSuperAdmin();
        $allocation->delete();
        return redirect()->route('allocations.index')->with('success', 'Alokasi kegiatan dihapus.');
    }

    // API: available RABs not yet allocated for a given budget (year)
    public function availableRabs(Request $request)
    {
        $this->ensureSuperAdmin();
        $budgetId = (int) $request->query('budget_id');
        if (!$budgetId) return response()->json(['data' => []]);

        $allocatedRabIds = BudgetAllocation::where('annual_budget_id', $budgetId)->pluck('rab_id');
        $rabs = Rab::whereNotIn('id', $allocatedRabIds)
            ->orderByDesc('created_at')
            ->limit(500)
            ->get(['id', 'kegiatan', 'rincian_menu', 'komponen']);

        return response()->json([
            'data' => $rabs->map(fn($r) => [
                'id' => $r->id,
                'label' => $r->kegiatan . ' (' . $r->rincian_menu . ' • ' . $r->komponen . ')',
            ]),
        ]);
    }

    // API: allocation summary by kegiatan and year
    public function summaryByKegiatan(Request $request)
    {
        $kegiatan = trim((string) $request->query('kegiatan', ''));
        $year = (int) ($request->query('year', date('Y')));
        if ($kegiatan === '') return response()->json(['data' => null]);

        $alloc = BudgetAllocation::with(['budget', 'rab'])
            ->whereHas('budget', fn($q) => $q->where('year', $year))
            ->whereHas('rab', fn($q) => $q->where('kegiatan', $kegiatan))
            ->latest()->first();
        if (!$alloc) return response()->json(['data' => null]);

        $realized = Lpj::where('kegiatan', $kegiatan)
            ->whereYear('created_at', $year)
            ->with('participants')
            ->get()
            ->sum(fn($lpj) => $lpj->participants->sum('total_amount'));

        // Add item opsional amounts
        $poaIds = Poa::where('rab_id', $alloc->rab_id)->pluck('id');
        if ($poaIds->isNotEmpty()) {
            $realized += (float) ItemOpsionalClaim::whereIn('poa_id', $poaIds)->sum('amount');
        }

        $remaining = (float) $alloc->allocated_amount - (float) $realized;

        return response()->json(['data' => [
            'annual_budget_id' => $alloc->annual_budget_id,
            'year' => $alloc->budget->year,
            'rab_id' => $alloc->rab_id,
            'kegiatan' => $alloc->rab->kegiatan,
            'allocated_amount' => (float) $alloc->allocated_amount,
            'realized_amount' => (float) $realized,
            'remaining_amount' => (float) $remaining,
        ]]);
    }
}
