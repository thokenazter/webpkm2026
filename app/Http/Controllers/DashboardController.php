<?php

namespace App\Http\Controllers;

use App\Models\Lpj;
use App\Models\LpjParticipant;
use App\Models\Employee;
use App\Models\Village;
use App\Models\Poa;
use App\Models\Rab;
use App\Models\RateSetting;
use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user && ($user->isSuperAdmin() || $user->isAdmin());
        $currentEmployeeId = $user?->employee_id ? (int) $user->employee_id : null;
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        $monthNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        // ─── Calendar Data (for all users) ───
        $poas = Poa::with(['rab', 'participants'])
            ->where('year', $currentYear)
            ->orderBy('kegiatan')
            ->get();

        $calendarData = [];
        $myActivitiesThisMonth = 0;
        $myUnclaimedThisMonth = 0;
        $myClaimedThisMonth = 0;

        foreach ($poas as $poa) {
            $schedule = $poa->schedule ?: ['months' => []];
            $months = $schedule['months'] ?? [];
            $generalParticipantIds = $poa->participants->pluck('employee_id')->filter()->map(fn($id) => (int) $id)->unique()->values()->all();

            $row = [
                'id' => $poa->id,
                'kegiatan' => $poa->kegiatan,
                'komponen' => $poa->rab?->komponen ?? '-',
                'rincian_menu' => $poa->rab?->rincian_menu ?? '-',
                'months' => [],
            ];

            for ($m = 1; $m <= 12; $m++) {
                $meta = $months[$m] ?? [];
                $count = (int) ($meta['count'] ?? 0);
                $marked = (bool) ($meta['marked'] ?? false);
                $claimed = !empty($meta['claimed_at']) || !empty($meta['sppt_lpj_id']) || !empty($meta['sppd_lpj_id']);
                $hasActivity = $count > 0 || $marked || $claimed;

                $tahap = null;
                if ($hasActivity) {
                    if ($m >= 1 && $m <= 4) $tahap = 1;
                    elseif ($m >= 5 && $m <= 8) $tahap = 2;
                    else $tahap = 3;
                }

                $monthParticipantIds = collect($meta['participant_ids'] ?? [])->map(fn($id) => (int) $id)->all();
                $effectiveIds = !empty($monthParticipantIds) ? $monthParticipantIds : $generalParticipantIds;
                $isMine = $currentEmployeeId && $hasActivity && !empty($effectiveIds) && in_array($currentEmployeeId, $effectiveIds, true);

                // Count personal stats for current month
                if ($m === $currentMonth && $isMine) {
                    $myActivitiesThisMonth++;
                    if ($claimed) $myClaimedThisMonth++;
                    else $myUnclaimedThisMonth++;
                }

                $row['months'][$m] = [
                    'has_activity' => $hasActivity,
                    'count' => $count,
                    'marked' => $marked,
                    'claimed' => $claimed,
                    'tahap' => $tahap,
                    'is_mine' => $isMine,
                ];
            }

            $calendarData[] = $row;
        }

        $availableKomponen = Rab::select('komponen')
            ->whereNotNull('komponen')
            ->distinct()
            ->orderBy('komponen')
            ->pluck('komponen')
            ->toArray();

        // ─── Personal greeting data ───
        $employeeName = null;
        if ($currentEmployeeId) {
            $employeeName = Employee::find($currentEmployeeId)?->nama;
        }
        $currentMonthName = $monthNames[$currentMonth] ?? '';

        // ─── Admin-only data (skip for regular users for performance) ───
        $totalEmployees = 0;
        $totalVillages = 0;
        $totalLpjs = 0;
        $totalBudget = 0;
        $monthlyLpjs = 0;
        $monthlyBudget = 0;
        $chartData = [];
        $lpjByType = collect();
        $budgetByType = collect();
        $topActivities = collect();
        $recentLpjs = collect();
        $transportTotal = 0;
        $perDiemTotal = 0;
        $currentTransportRate = 0;
        $currentPerDiemRate = 0;
        $activeEmployees = 0;

        if ($isAdmin) {
            $totalEmployees = Employee::count();
            $totalVillages = Village::count();
            $totalLpjs = Lpj::count();
            $totalBudget = LpjParticipant::sum('total_amount');

            $now = Carbon::now();
            $monthlyLpjs = $this->getLpjCountByActivityMonth($now->month, $now->year, $user, true);
            $monthlyBudget = $this->getBudgetByActivityMonth($now->month, $now->year, $user, true);
            $chartData = $this->getChartDataDynamic($user, true);

            $lpjByType = Lpj::select('type', DB::raw('count(*) as count'))
                ->groupBy('type')->get()
                ->mapWithKeys(fn($item) => [$item->type => $item->count]);

            $budgetByType = LpjParticipant::join('lpjs', 'lpj_participants.lpj_id', '=', 'lpjs.id')
                ->select('lpjs.type', DB::raw('sum(lpj_participants.total_amount) as total'))
                ->groupBy('lpjs.type')->get()
                ->mapWithKeys(fn($item) => [$item->type => $item->total]);

            $topActivities = LpjParticipant::join('lpjs', 'lpj_participants.lpj_id', '=', 'lpjs.id')
                ->select('lpjs.kegiatan', DB::raw('sum(lpj_participants.total_amount) as total'))
                ->groupBy('lpjs.kegiatan')->orderBy('total', 'desc')->limit(5)->get();

            $recentLpjs = Lpj::with(['participants', 'createdBy'])->latest()->take(5)->get()
                ->map(function ($lpj) {
                    $lpj->total_budget = $lpj->participants->sum('total_amount');
                    $lpj->participant_count = $lpj->participants->count();
                    return $lpj;
                });

            $transportTotal = LpjParticipant::sum('transport_amount');
            $perDiemTotal = LpjParticipant::sum('per_diem_amount');
            $currentTransportRate = RateSetting::getTransportRate();
            $currentPerDiemRate = RateSetting::getPerDiemRate();
            $activeEmployees = LpjParticipant::distinct('employee_id')->count('employee_id');
        }

        return view('dashboard', compact(
            'isAdmin',
            'calendarData',
            'availableKomponen',
            'myActivitiesThisMonth',
            'myUnclaimedThisMonth',
            'myClaimedThisMonth',
            'employeeName',
            'currentMonthName',
            'currentYear',
            'totalEmployees',
            'totalVillages',
            'totalLpjs',
            'totalBudget',
            'monthlyLpjs',
            'monthlyBudget',
            'chartData',
            'lpjByType',
            'budgetByType',
            'topActivities',
            'recentLpjs',
            'transportTotal',
            'perDiemTotal',
            'currentTransportRate',
            'currentPerDiemRate',
            'activeEmployees'
        ));
    }

    /**
     * Get LPJ count by activity month
     */
    private function getLpjCountByActivityMonth($month, $year, $user, $isAdmin)
    {
        $lpjs = Lpj::when(!$isAdmin, fn($q) => $q->where('created_by', $user->id))->get();
        $count = 0;

        foreach ($lpjs as $lpj) {
            $activityDate = DateHelper::getMonthYearFromActivity($lpj->tanggal_kegiatan);

            // Fallback ke tanggal surat jika parsing tanggal kegiatan gagal
            if (!$activityDate) {
                $activityDate = DateHelper::getMonthYearFromDocument($lpj->tanggal_surat);
            }

            if ($activityDate && $activityDate['month'] == $month && $activityDate['year'] == $year) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get budget sum by activity month
     */
    private function getBudgetByActivityMonth($month, $year, $user, $isAdmin)
    {
        $total = 0;
        $lpjs = Lpj::with('participants')->when(!$isAdmin, fn($q) => $q->where('created_by', $user->id))->get();

        foreach ($lpjs as $lpj) {
            $activityDate = DateHelper::getMonthYearFromActivity($lpj->tanggal_kegiatan);

            // Fallback ke tanggal surat jika parsing tanggal kegiatan gagal
            if (!$activityDate) {
                $activityDate = DateHelper::getMonthYearFromDocument($lpj->tanggal_surat);
            }

            if ($activityDate && $activityDate['month'] == $month && $activityDate['year'] == $year) {
                $total += $lpj->participants->sum('total_amount');
            }
        }

        return $total;
    }

    /**
     * Get dynamic chart data that includes months with activities
     */
    private function getChartDataDynamic($user, $isAdmin)
    {
        // Kumpulkan semua bulan yang ada kegiatan
        $monthsWithActivity = [];
        $lpjs = Lpj::with('participants')
            ->when(!$isAdmin, fn($q) => $q->where('created_by', $user->id))
            ->get();

        foreach ($lpjs as $lpj) {
            $activityDate = DateHelper::getMonthYearFromActivity($lpj->tanggal_kegiatan);
            
            // Fallback ke tanggal surat jika parsing tanggal kegiatan gagal
            if (!$activityDate) {
                $activityDate = DateHelper::getMonthYearFromDocument($lpj->tanggal_surat);
            }

            if ($activityDate) {
                $key = $activityDate['year'] . '-' . sprintf('%02d', $activityDate['month']);
                if (!isset($monthsWithActivity[$key])) {
                    $monthsWithActivity[$key] = [
                        'month' => $activityDate['month'],
                        'year' => $activityDate['year'],
                        'total' => 0
                    ];
                }
                $monthsWithActivity[$key]['total'] += $lpj->participants->sum('total_amount');
            }
        }

        // Sort by year-month
        ksort($monthsWithActivity);

        // Ambil 6 bulan terakhir dari data yang ada, atau minimal 6 bulan terakhir dari sekarang
        $currentDate = Carbon::now();
        $chartData = [];

        // Jika ada data kegiatan, gunakan range yang mencakup data tersebut
        if (!empty($monthsWithActivity)) {
            $allMonths = array_keys($monthsWithActivity);
            $latestMonth = end($allMonths);
            $earliestMonth = reset($allMonths);

            // Buat range 6 bulan yang mencakup data terbaru
            $endDate = Carbon::createFromFormat('Y-m', $latestMonth);
            $startDate = $endDate->copy()->subMonths(5);

            // Jika ada data di masa depan (seperti September), pastikan termasuk
            for ($i = 0; $i < 6; $i++) {
                $date = $startDate->copy()->addMonths($i);
                $key = $date->format('Y-m');
                $monthName = $date->format('M Y');
                
                $total = isset($monthsWithActivity[$key]) ? $monthsWithActivity[$key]['total'] : 0;
                
                $chartData[] = [
                    'month' => $monthName,
                    'total' => $total
                ];
            }
        } else {
            // Fallback ke 6 bulan terakhir biasa jika tidak ada data
            for ($i = 5; $i >= 0; $i--) {
                $date = $currentDate->copy()->subMonths($i);
                $monthName = $date->format('M Y');
                
                $chartData[] = [
                    'month' => $monthName,
                    'total' => 0
                ];
            }
        }

        return $chartData;
    }
}
