<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumans = Pengumuman::orderBy('created_at', 'desc')->paginate(10);
        return view('pengumuman.index', compact('pengumumans'));
    }

    public function create()
    {
        return view('pengumuman.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'is_active' => 'boolean',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'prioritas' => 'required|in:low,medium,high',
            'warna_tema' => 'required|string|max:7'
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Normalize dates for immediate activation if requested
        if ($validated['is_active']) {
            $now = Carbon::now()->startOfDay();
            if (!empty($validated['tanggal_mulai'])) {
                $tm = Carbon::parse($validated['tanggal_mulai']);
                if ($tm->greaterThan($now)) {
                    $validated['tanggal_mulai'] = $now;
                }
            }
            if (!empty($validated['tanggal_selesai'])) {
                $ts = Carbon::parse($validated['tanggal_selesai']);
                if ($ts->lessThan($now)) {
                    $validated['tanggal_selesai'] = null;
                }
            }
        }

        Pengumuman::create($validated);

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function show(Pengumuman $pengumuman)
    {
        return view('pengumuman.show', compact('pengumuman'));
    }

    public function edit(Pengumuman $pengumuman)
    {
        return view('pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'is_active' => 'boolean',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'prioritas' => 'required|in:low,medium,high',
            'warna_tema' => 'required|string|max:7'
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($validated['is_active']) {
            $now = Carbon::now()->startOfDay();
            if (!empty($validated['tanggal_mulai'])) {
                $tm = Carbon::parse($validated['tanggal_mulai']);
                if ($tm->greaterThan($now)) {
                    $validated['tanggal_mulai'] = $now;
                }
            }
            if (!empty($validated['tanggal_selesai'])) {
                $ts = Carbon::parse($validated['tanggal_selesai']);
                if ($ts->lessThan($now)) {
                    $validated['tanggal_selesai'] = null;
                }
            }
        }

        $pengumuman->update($validated);

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function toggle(Pengumuman $pengumuman)
    {
        $newActive = !$pengumuman->is_active;
        $updates = ['is_active' => $newActive];

        if ($newActive) {
            $now = Carbon::now()->startOfDay();
            // Jika tanggal_mulai di masa depan, majukan ke hari ini agar langsung aktif
            if ($pengumuman->tanggal_mulai && $pengumuman->tanggal_mulai->greaterThan($now)) {
                $updates['tanggal_mulai'] = $now;
            }
            // Jika tanggal_selesai sudah lewat, kosongkan supaya tidak menghalangi
            if ($pengumuman->tanggal_selesai && $pengumuman->tanggal_selesai->lessThan($now)) {
                $updates['tanggal_selesai'] = null;
            }
        }

        $pengumuman->update($updates);

        $status = $newActive ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('pengumuman.index')->with('success', "Pengumuman berhasil {$status}.");
    }

    public function getActive()
    {
        // Order by custom priority (high > medium > low) then by newest
        $pengumuman = Pengumuman::active()
            ->orderByRaw("CASE prioritas WHEN 'high' THEN 3 WHEN 'medium' THEN 2 ELSE 1 END DESC")
            ->orderBy('created_at', 'desc')
            ->first();
            
        return response()->json($pengumuman);
    }
}
