<?php

namespace App\Http\Controllers\Pkm;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\View\View;

class TimLayananController extends Controller
{
    /**
     * Display the Tim Layanan page.
     */
    public function index(): View
    {
        // Get all active staff with responsibilities and cluster
        $allStaff = Staff::active()
            ->with(['responsibilities', 'cluster'])
            ->orderBy('order')
            ->get();

        // Total staff statistics
        $totalStaff = $allStaff->count();

        // Group by cluster for the PJ & Koordinator tab section
        $clusterCount = $allStaff->pluck('cluster_id')->unique()->filter()->count();

        return view('pkm.tim-layanan', [
            'allStaff' => $allStaff,
            'totalStaff' => $totalStaff,
            'clusterCount' => $clusterCount,
        ]);
    }
}
