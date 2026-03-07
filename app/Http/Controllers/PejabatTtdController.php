<?php

namespace App\Http\Controllers;

use App\Http\Requests\PejabatTtdRequest;
use App\Models\PejabatTtd;
use Illuminate\Http\Request;

class PejabatTtdController extends Controller
{
    public function index()
    {
        $pejabatTtds = PejabatTtd::latest()->paginate(10);
        return view('pejabat-ttd.index', compact('pejabatTtds'));
    }

    public function create()
    {
        return view('pejabat-ttd.create');
    }

    public function store(PejabatTtdRequest $request)
    {
        PejabatTtd::create($request->validated());

        return redirect()->route('pejabat-ttd.index')
            ->with('success', 'Pejabat TTD berhasil ditambahkan.');
    }

    public function show(PejabatTtd $pejabatTtd)
    {
        return view('pejabat-ttd.show', compact('pejabatTtd'));
    }

    public function edit(PejabatTtd $pejabatTtd)
    {
        return view('pejabat-ttd.edit', compact('pejabatTtd'));
    }

    public function update(PejabatTtdRequest $request, PejabatTtd $pejabatTtd)
    {
        $pejabatTtd->update($request->validated());

        return redirect()->route('pejabat-ttd.index')
            ->with('success', 'Pejabat TTD berhasil diperbarui.');
    }

    public function destroy(PejabatTtd $pejabatTtd)
    {
        $pejabatTtd->delete();

        return redirect()->route('pejabat-ttd.index')
            ->with('success', 'Pejabat TTD berhasil dihapus.');
    }
}