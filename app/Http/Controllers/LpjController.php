<?php

namespace App\Http\Controllers;

use App\Models\Lpj;
use App\Models\Employee;
use App\Models\Village;
use App\Models\Activity;
use App\Models\BudgetAllocation;
// PerDiemRate tidak diperlukan karena uang harian fixed Rp 150.000 per desa
use App\Http\Requests\StoreLpjRequest;
use App\Services\LpjDocumentService;
use App\Services\TibaBerangkatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Services\ScheduleGeneratorService;

class LpjController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Authorize listing (policy)
        $this->authorize('viewAny', Lpj::class);

        $user = $request->user();
        $isAdmin = $user && ($user->isSuperAdmin() || $user->email === 'admin@admin.com');

        $query = Lpj::with(['participants.employee', 'participants.borrowedEmployee'])
            ->when(!$isAdmin, fn($q) => $q->where('created_by', $user->id));
        
        // Live search by kegiatan, no_surat, atau tanggal
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                  ->orWhere('no_surat', 'like', "%{$search}%")
                  ->orWhere('tanggal_kegiatan', 'like', "%{$search}%")
                  ->orWhere('tanggal_surat', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by month/year
        if ($request->filled('month') && $request->filled('year')) {
            // Since tanggal_kegiatan is stored as text, we'll filter by contains
            $monthName = $this->getMonthName($request->month);
            $query->where('tanggal_kegiatan', 'like', "%{$monthName} {$request->year}%");
        }

        // Filter by date range
        if ($request->filled('date_from') || $request->filled('date_to')) {
            // For date range, we'll use created_at as fallback since tanggal_kegiatan is text
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        // Pagination or show all
        if ($request->get('show_all') === 'true') {
            $lpjs = $query->get();
            $showAll = true;
        } else {
            $perPage = $request->get('per_page', 10);
            $lpjs = $query->paginate($perPage);
            $showAll = false;
        }

        // Calculate statistics
        $stats = $this->calculateStats($query);
        
        // If it's an AJAX request, return only the table content
        if ($request->ajax()) {
            return view('lpjs.partials.table', compact('lpjs', 'showAll'))->render();
        }
        
        return view('lpjs.index', compact('lpjs', 'stats', 'showAll'));
    }

    /**
     * Calculate statistics for current filtered data
     */
    private function calculateStats($query)
    {
        // Clone query to avoid affecting pagination
        $statsQuery = clone $query;
        $allLpjs = $statsQuery->with('participants')->get();

        return [
            'total_lpjs' => $allLpjs->count(),
            'total_transport' => $allLpjs->sum(function($lpj) { 
                return $lpj->participants->sum('transport_amount'); 
            }),
            'total_per_diem' => $allLpjs->sum(function($lpj) { 
                return $lpj->participants->sum('per_diem_amount'); 
            }),
            'total_amount' => $allLpjs->sum(function($lpj) { 
                return $lpj->participants->sum('total_amount'); 
            }),
            'total_participants' => $allLpjs->sum(function($lpj) { 
                return $lpj->participants->count(); 
            }),
            'by_type' => $allLpjs->groupBy('type')->map->count(),
            'avg_per_lpj' => $allLpjs->count() > 0 ? $allLpjs->sum(function($lpj) { 
                return $lpj->participants->sum('total_amount'); 
            }) / $allLpjs->count() : 0,
        ];
    }

    /**
     * Get month name in Indonesian
     */
    private function getMonthName($monthNumber)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $months[$monthNumber] ?? '';
    }

    /**
     * Bulk delete LPJs
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'lpj_ids' => 'required|array',
            'lpj_ids.*' => 'exists:lpjs,id'
        ]);

        $deletedCount = 0;
        $errors = [];

        foreach ($request->lpj_ids as $lpjId) {
            try {
                $lpj = Lpj::findOrFail($lpjId);
                $this->authorize('delete', $lpj);
                $lpj->delete();
                $deletedCount++;
            } catch (\Exception $e) {
                $errors[] = "LPJ ID {$lpjId}: " . $e->getMessage();
            }
        }

        if ($deletedCount > 0) {
            $message = "Berhasil menghapus {$deletedCount} LPJ.";
            if (!empty($errors)) {
                $message .= " Beberapa LPJ gagal dihapus: " . implode(', ', $errors);
            }
            return back()->with('success', $message);
        } else {
            return back()->with('error', 'Gagal menghapus LPJ: ' . implode(', ', $errors));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::all();
        
        return view('lpjs.create', compact('employees'));
    }

    /**
     * Search employees for autocomplete
     */
    public function searchEmployees(Request $request)
    {
        $search = $request->get('q', '');
        
        $employees = Employee::where('nama', 'LIKE', "%{$search}%")
            ->orWhere('pangkat_golongan', 'LIKE', "%{$search}%")
            ->orWhere('nip', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id', 'nama', 'pangkat_golongan', 'nip']);
            
        return response()->json([
            'results' => $employees->map(function($employee) {
                return [
                    'id' => $employee->id,
                    'text' => $employee->nama . ' (' . $employee->pangkat_golongan . ')',
                    'nama' => $employee->nama,
                    'pangkat_golongan' => $employee->pangkat_golongan,
                    'nip' => $employee->nip
                ];
            })
        ]);
    }

    /**
     * Search activities for autocomplete
     */
    public function searchActivities(Request $request)
    {
        $search = trim($request->get('q', ''));
        
        if (empty($search)) {
            return response()->json(['results' => []]);
        }
        
        // Search existing activities
        $activities = Activity::where('name', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name']);
        
        $results = $activities->map(function($activity) {
            return [
                'id' => $activity->name,
                'text' => $activity->name,
                'name' => $activity->name,
                'existing' => true
            ];
        });
        
        // If no exact match found and search is not empty, suggest to create new
        $exactMatch = $activities->where('name', $search)->first();
        if (!$exactMatch && strlen($search) >= 3) {
            $results->prepend([
                'id' => $search,
                'text' => $search . ' (Buat Baru)',
                'name' => $search,
                'existing' => false,
                'new_activity' => true
            ]);
        }
        
        return response()->json([
            'results' => $results->values()
        ]);
    }

    /**
     * Create new activity if it doesn't exist
     */
    public function createActivityIfNotExists($activityName)
    {
        $activityName = trim($activityName);
        
        if (empty($activityName)) {
            return null;
        }
        
        // Check if activity already exists (case insensitive)
        $existingActivity = Activity::whereRaw('LOWER(name) = ?', [strtolower($activityName)])->first();
        
        if (!$existingActivity) {
            // Create new activity
            $newActivity = Activity::create([
                'name' => $activityName
            ]);
            
            return $newActivity;
        }
        
        return $existingActivity;
    }

    /**
     * Create new activity via AJAX
     */
    public function createActivity(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|min:3'
        ]);

        $activityName = trim($request->nama);
        
        // Check if activity already exists (case insensitive)
        $existingActivity = Activity::whereRaw('LOWER(name) = ?', [strtolower($activityName)])->first();
        
        if ($existingActivity) {
            return response()->json([
                'success' => true,
                'activity' => [
                    'id' => $existingActivity->name,
                    'text' => $existingActivity->name,
                    'name' => $existingActivity->name,
                    'existing' => true
                ],
                'message' => 'Kegiatan sudah ada sebelumnya.'
            ]);
        }

        // Create new activity
        $newActivity = Activity::create([
            'name' => $activityName
        ]);

        return response()->json([
            'success' => true,
            'activity' => [
                'id' => $newActivity->name,
                'text' => $newActivity->name,
                'name' => $newActivity->name,
                'existing' => false,
                'new_activity' => true
            ],
            'message' => 'Kegiatan baru berhasil ditambahkan!'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLpjRequest $request)
    {
        $lpj = null;
        // Allocation enforcement (if allocation exists for kegiatan & current year)
        $this->enforceAllocationLimit($request, null);
        
        DB::transaction(function () use ($request, &$lpj) {
            // Auto-create activity if it doesn't exist
            $this->createActivityIfNotExists($request->kegiatan);
            
            $lpj = Lpj::create([
                'type' => $request->type,
                'kegiatan' => $request->kegiatan,
                'no_surat' => $request->no_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'tanggal_kegiatan' => $request->tanggal_kegiatan,
                'transport_mode' => $request->transport_mode,
                'jumlah_desa_darat' => $request->jumlah_desa_darat,
                'desa_tujuan_darat' => $request->desa_tujuan_darat,
                'jumlah_desa_seberang' => $request->jumlah_desa_seberang,
                'desa_tujuan_seberang' => $request->desa_tujuan_seberang,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->participants as $participantData) {
                $lpj->participants()->create([
                    'employee_id' => $participantData['employee_id'],
                    'credited_employee_id' => $participantData['credited_employee_id'] ?? $participantData['employee_id'],
                    'role' => $participantData['role'],
                    'lama_tugas_hari' => $participantData['lama_tugas_hari'],
                    'transport_amount' => $participantData['transport_amount'],
                    'per_diem_rate' => $participantData['per_diem_rate'],
                    'per_diem_days' => $participantData['per_diem_days'],
                    'per_diem_amount' => $participantData['per_diem_amount'],
                    'total_amount' => $participantData['transport_amount'] + $participantData['per_diem_amount'],
                ]);
            }
        });

        // Auto-generate Word document
        try {
            // Load participants for document generation
            $lpj->load(['participants.employee', 'participants.borrowedEmployee']);
            
            $documentService = new LpjDocumentService();
            $documentService->generateDocument($lpj);
            $message = 'LPJ berhasil dibuat dan dokumen Word telah digenerate!';
        } catch (\Exception $e) {
            $message = 'LPJ berhasil dibuat, namun gagal membuat dokumen Word: ' . $e->getMessage();
        }

        $redirect = redirect()->route('lpjs.index')
            ->with('success', $message)
            ->with('show_download', $lpj->id);

        // If just created SPPT, suggest continuing to create SPPD
        if ($lpj && $lpj->type === 'SPPT') {
            $redirect->with('suggest_sppd_for', $lpj->id)
                     ->with('suggest_tb_from', $lpj->id); // allow TB suggestion after dismiss
        }

        // If this LPJ was created as continuation (e.g., SPPD from SPPT), provide pair download
        $sourceId = $request->input('source_lpj_id');
        if ($sourceId) {
            // Validate source exists
            try {
                $source = Lpj::find($sourceId);
                if ($source) {
                    $redirect->with('download_pair_ids', [$source->id, $lpj->id]);

                    // If we now have an SPPT + SPPD pair, try auto-creating Tiba Berangkat
                    $sppt = $lpj->type === 'SPPT' ? $lpj : ($source->type === 'SPPT' ? $source : null);
                    $sppd = $lpj->type === 'SPPD' ? $lpj : ($source->type === 'SPPD' ? $source : null);
                    if ($sppt && $sppd) {
                        try {
                            $tbService = new TibaBerangkatService();
                            $tb = $tbService->createFromLpjs($sppt, $sppd);
                            if ($tb) {
                                $redirect->with('tiba_berangkat_review_id', $tb->id)
                                         ->with('tiba_berangkat_id', $tb->id);
                            }
                        } catch (\Throwable $e) {
                            // best-effort; ignore failures
                        }
                    }
                }
            } catch (\Exception $e) {
                // Ignore if not found
            }
        }

        // If user created SPPD first (not from SPPT), suggest creating Tiba Berangkat too
        if ($lpj && $lpj->type === 'SPPD' && empty($sourceId)) {
            // Auto-create TB from SPPD-only and show review modal
            try {
                $tbService = new TibaBerangkatService();
                $tb = $tbService->createFromSingleLpj($lpj);
                if ($tb) {
                    $redirect->with('tiba_berangkat_review_id', $tb->id)
                             ->with('tiba_berangkat_id', $tb->id);
                } else {
                    // Fallback to suggestion if auto-create not possible
                    $redirect->with('suggest_tb_from', $lpj->id);
                }
            } catch (\Throwable $e) {
                $redirect->with('suggest_tb_from', $lpj->id);
            }
        }

        return $redirect;
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Lpj $lpj)
    {
        $this->authorize('view', $lpj);
        $lpj->load(['participants.employee', 'participants.borrowedEmployee', 'createdBy']);
        return view('lpjs.show', compact('lpj'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Lpj $lpj)
    {
        $this->authorize('update', $lpj);
        $employees = Employee::all();
        
        $lpj->load(['participants.employee', 'participants.borrowedEmployee']);
        
        return view('lpjs.edit', compact('lpj', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLpjRequest $request, Lpj $lpj)
    {
        $this->authorize('update', $lpj);
        // Allocation enforcement (exclude current LPJ from realized)
        $this->enforceAllocationLimit($request, $lpj->id);
        DB::transaction(function () use ($request, $lpj) {
            // Auto-create activity if it doesn't exist
            $this->createActivityIfNotExists($request->kegiatan);
            
            $lpj->update([
                'type' => $request->type,
                'kegiatan' => $request->kegiatan,
                'no_surat' => $request->no_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'tanggal_kegiatan' => $request->tanggal_kegiatan,
                'transport_mode' => $request->transport_mode,
                'jumlah_desa_darat' => $request->jumlah_desa_darat,
                'desa_tujuan_darat' => $request->desa_tujuan_darat,
                'jumlah_desa_seberang' => $request->jumlah_desa_seberang,
                'desa_tujuan_seberang' => $request->desa_tujuan_seberang,
            ]);

            // Delete existing participants
            $lpj->participants()->delete();

            // Create new participants
            foreach ($request->participants as $participantData) {
                $lpj->participants()->create([
                    'employee_id' => $participantData['employee_id'],
                    'credited_employee_id' => $participantData['credited_employee_id'] ?? $participantData['employee_id'],
                    'role' => $participantData['role'],
                    'lama_tugas_hari' => $participantData['lama_tugas_hari'],
                    'transport_amount' => $participantData['transport_amount'],
                    'per_diem_rate' => $participantData['per_diem_rate'],
                    'per_diem_days' => $participantData['per_diem_days'],
                    'per_diem_amount' => $participantData['per_diem_amount'],
                    'total_amount' => $participantData['transport_amount'] + $participantData['per_diem_amount'],
                ]);
            }
        });

        return redirect()->route('lpjs.index')
            ->with('success', 'LPJ berhasil diperbarui.');
    }

    /**
     * Enforce allocation remaining amount when saving LPJ.
     * Allows when no allocation exists; blocks when allocation exists and new total would exceed remaining.
     */
    private function enforceAllocationLimit(Request $request, ?int $excludeLpjId = null): void
    {
        try {
            $kegiatan = trim((string) $request->kegiatan);
            if ($kegiatan === '') return; // nothing to check

            $year = (int) date('Y');
            $alloc = BudgetAllocation::with(['budget','rab'])
                ->whereHas('budget', fn($q) => $q->where('year', $year))
                ->whereHas('rab', fn($q) => $q->where('kegiatan', $kegiatan))
                ->latest()->first();
            if (!$alloc) return; // no allocation configured → allow

            // Compute new total from request participants
            $newTotal = 0.0;
            foreach ((array) $request->participants as $p) {
                $transport = isset($p['transport_amount']) ? (float) $p['transport_amount'] : 0.0;
                $perDiem = isset($p['per_diem_amount']) ? (float) $p['per_diem_amount'] : 0.0;
                $newTotal += ($transport + $perDiem);
            }

            // Realized total in the same year, same kegiatan, excluding current LPJ if provided
            $realized = Lpj::where('kegiatan', $kegiatan)
                ->whereYear('created_at', $year)
                ->when($excludeLpjId, fn($q) => $q->where('id', '!=', $excludeLpjId))
                ->with('participants')
                ->get()
                ->sum(fn($l) => $l->participants->sum('total_amount'));

            $remaining = (float) $alloc->allocated_amount - (float) $realized;
            if ($newTotal > $remaining) {
                throw ValidationException::withMessages([
                    'kegiatan' => 'Sisa alokasi untuk kegiatan ini pada tahun ' . $year . ' tidak mencukupi. Sisa: Rp ' . number_format($remaining, 0, ',', '.') . ', rencana LPJ: Rp ' . number_format($newTotal, 0, ',', '.') . '.',
                ]);
            }
        } catch (\Throwable $e) {
            // Fail open: do not block save due to enforcement error
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Lpj $lpj)
    {
        $this->authorize('delete', $lpj);
        $lpj->delete();

        return redirect()->route('lpjs.index')
            ->with('success', 'LPJ berhasil dihapus.');
    }

    /**
     * Create form prefilled from an existing LPJ (e.g., SPPT -> SPPD)
     */
    public function createFrom(Request $request, Lpj $lpj)
    {
        $this->authorize('view', $lpj);
        $employees = Employee::all();

        // Determine target type (default to SPPD)
        $targetType = strtoupper($request->get('to', 'SPPD'));
        if (!in_array($targetType, ['SPPT', 'SPPD'])) {
            $targetType = 'SPPD';
        }

        // Prefill data: copy kegiatan and participants, leave dates/no_surat empty to force edit
        $lpj->load('participants');
        $prefill = [
            'type' => $targetType,
            'kegiatan' => $lpj->kegiatan,
            'no_surat' => '',
            'tanggal_surat' => '',
            'tanggal_kegiatan' => '',
            'participants' => $lpj->participants->map(function ($p) {
                return [
                    'employee_id' => $p->employee_id,
                    'role' => $p->role,
                ];
            })->values(),
            // Leave transport_mode and desa fields to defaults handled by UI based on type
        ];

        $sourceLpjId = $lpj->id;
        return view('lpjs.create', compact('employees', 'prefill', 'sourceLpjId'));
    }

    /**
     * Handle Auto Schedule trigger from UI.
     */
    public function autoSchedule(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        $month = $request->month;
        $year = $request->year;

        \Carbon\Carbon::setLocale('id'); // Force Indonesian for date names

        $scheduler = new ScheduleGeneratorService();

        // Fetch LPJs created in this month/year that need scheduling
        // Note: We might want to filter only those that don't have dates yet?
        // Or re-schedule everything? "Pola ... di puskesmas saya" suggests a batch process.
        // Let's assume re-schedule ALL for that month to ensure consistency.
        $lpjs = Lpj::whereMonth('created_at', $month)
                   ->whereYear('created_at', $year)
                   ->get();

        if ($lpjs->isEmpty()) {
            return back()->with('error', 'Tidak ada data LPJ yang ditemukan untuk bulan/tahun tersebut.');
        }

        $bookingIndex = [];
        $rescheduledCount = 0;

        DB::transaction(function () use ($lpjs, $scheduler, $month, $year, &$bookingIndex, &$rescheduledCount) {
            foreach ($lpjs as $lpj) {
                // Heuristic for Duration
                $totalDesa = ($lpj->jumlah_desa_darat ?? 0) + ($lpj->jumlah_desa_seberang ?? 0);
                $duration = $totalDesa > 2 ? 3 : 1; 

                $rawParticipants = $lpj->participants->pluck('employee_id')->toArray();
                if (empty($rawParticipants)) continue;

                $startDate = $scheduler->findAvailableDate(
                    $rawParticipants,
                    $duration,
                    $month,
                    $year,
                    $bookingIndex
                );

                if ($startDate) {
                    $endDate = $startDate->copy()->addDays($duration - 1);

                    // Update Index
                    $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
                    foreach ($rawParticipants as $empId) {
                        if (!isset($bookingIndex[$empId])) $bookingIndex[$empId] = [];
                        foreach ($period as $dt) {
                            $bookingIndex[$empId][] = $dt->toDateString();
                        }
                    }

                    // Update LPJ
                    $lpj->start_date = $startDate;
                    $lpj->end_date = $endDate;
                    
                     if ($duration > 1) {
                        $lpj->tanggal_kegiatan = $startDate->format('d') . ' s/d ' . $endDate->translatedFormat('d F Y');
                    } else {
                        $lpj->tanggal_kegiatan = $startDate->translatedFormat('d F Y');
                    }
                    $lpj->save();
                    $rescheduledCount++;
                }
            }

            // Generate numbers
            $scheduler->generateSuratNumbers($month, $year);
        });

        return back()->with('success', "Berhasil menjadwalkan ulang {$rescheduledCount} kegiatan dan memperbarui nomor surat.");
    }
}
