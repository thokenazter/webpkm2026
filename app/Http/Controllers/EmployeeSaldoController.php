<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LpjParticipant;
use Illuminate\Http\Request;

class EmployeeSaldoController extends Controller
{
    /**
     * Display employee salary/balance list
     */
    public function index(Request $request)
    {
        $this->authorize('viewSaldoAny', \App\Models\Employee::class);

        $user = $request->user();
        $isAdmin = $user && $user->isAdmin();

        $query = Employee::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('pangkat_golongan', 'like', "%{$search}%");
            });
        }

        // Apply scoping: non-admins only see saldo from LPJ they created
        if (!$isAdmin) {
            // Kegiatan LPJ yang dibuat oleh user (untuk menyamakan scope dengan LPJ)
            $userLpjKegiatan = \App\Models\Lpj::where('created_by', $user->id)
                ->pluck('kegiatan')
                ->filter()
                ->unique();
            $query->whereHas('creditedLpjParticipants.lpj', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            });

            // Precompute scoped sums and counts for performance and view usage
            $query->withSum([
                'creditedLpjParticipants as total_transport_scoped' => function ($q) use ($user) {
                    $q->whereHas('lpj', fn($l) => $l->where('created_by', $user->id));
                }
            ], 'transport_amount')
            ->withSum([
                'creditedLpjParticipants as total_per_diem_scoped' => function ($q) use ($user) {
                    $q->whereHas('lpj', fn($l) => $l->where('created_by', $user->id));
                }
            ], 'per_diem_amount')
            ->withSum([
                'creditedLpjParticipants as total_saldo_scoped' => function ($q) use ($user) {
                    $q->whereHas('lpj', fn($l) => $l->where('created_by', $user->id));
                }
            ], 'total_amount')
            ->withSum([
                'saldoEntries as total_optional_scoped' => function ($q) use ($userLpjKegiatan) {
                    // Scope optional saldo ke POA yang kegiatannya berada dalam LPJ buatan user
                    $q->whereHas('poa', function ($l) use ($userLpjKegiatan) {
                        $l->whereIn('kegiatan', $userLpjKegiatan);
                    });
                }
            ], 'amount')
            ->withCount([
                'lpjParticipants as total_lpj_count_scoped' => function ($q) use ($user) {
                    $q->whereHas('lpj', fn($l) => $l->where('created_by', $user->id));
                }
            ]);
        } else {
            // Admins: only employees that have been used in LPJ (any creator)
            $query->whereHas('creditedLpjParticipants')
                ->withSum('creditedLpjParticipants as total_transport_scoped', 'transport_amount')
                ->withSum('creditedLpjParticipants as total_per_diem_scoped', 'per_diem_amount')
                ->withSum('creditedLpjParticipants as total_saldo_scoped', 'total_amount')
                ->withSum('saldoEntries as total_optional_scoped', 'amount')
                ->withCount('creditedLpjParticipants as total_lpj_count_scoped');
        }

        // Sort by combined scoped total saldo (LPJ + optional)
        $employees = $query->get()->sortByDesc(function ($e) {
            return (float) ($e->total_saldo_scoped ?? 0) + (float) ($e->total_optional_scoped ?? 0);
        });

        // Calculate summary statistics (scoped)
        $totalEmployees = $employees->count();
        $totalSaldo = $employees->sum(function ($e) {
            return (float) ($e->total_saldo_scoped ?? 0) + (float) ($e->total_optional_scoped ?? 0);
        });
        $avgSaldo = $totalEmployees > 0 ? $totalSaldo / $totalEmployees : 0;

        return view('employee-saldo.index', compact(
            'employees', 
            'totalEmployees', 
            'totalSaldo', 
            'avgSaldo'
        ));
    }
    
    /**
     * Show detailed salary breakdown for specific employee
     */
    public function show(Request $request, Employee $employee)
    {
        $this->authorize('viewSaldo', $employee);

        $user = $request->user();
        $isAdmin = $user && $user->isAdmin();

        // Load participations.
        // For non-admins: if viewing own saldo, include all; otherwise scope to LPJ created by the user
        $isSelf = !is_null($user->employee_id) && (int)$user->employee_id === (int)$employee->id;
        $participations = $employee->creditedLpjParticipants()
            ->when(!$isAdmin && !$isSelf, function ($q) use ($user) {
                $q->whereHas('lpj', fn($l) => $l->where('created_by', $user->id));
            })
            ->with(['lpj'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Compute scoped totals for display
        $totalTransport = $participations->sum('transport_amount');
        $totalPerDiem = $participations->sum('per_diem_amount');
        $totalSaldoLpj = $participations->sum('total_amount');

        // Load optional entries (snack/konsumsi/penggandaan/lainnya)
        $optionalEntries = $employee->saldoEntries()
            ->when(!$isAdmin && !$isSelf, function ($q) use ($user) {
                // Samakan scope dengan LPJ: hanya dari kegiatan yang LPJ-nya dibuat oleh user
                $userLpjKegiatan = \App\Models\Lpj::where('created_by', $user->id)
                    ->pluck('kegiatan')
                    ->filter()
                    ->unique();
                $q->whereHas('poa', function ($l) use ($userLpjKegiatan) {
                    $l->whereIn('kegiatan', $userLpjKegiatan);
                });
            })
            ->orderByDesc('year')->orderByDesc('month')->orderByDesc('id')
            ->get();
        $totalOptional = $optionalEntries->sum('amount');
        $totalSaldo = (float) $totalSaldoLpj + (float) $totalOptional;

        return view('employee-saldo.show', compact('employee', 'participations', 'optionalEntries', 'totalTransport', 'totalPerDiem', 'totalSaldo', 'totalOptional', 'totalSaldoLpj'));
    }
}
