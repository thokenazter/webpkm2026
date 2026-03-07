<?php

namespace App\Http\Controllers;

use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VillageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $villages = Village::latest()->paginate(10);
        return view('villages.index', compact('villages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('villages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'akses' => 'required|in:DARAT,SEBERANG',
            'transport_standard' => 'required|numeric|min:0',
        ]);

        Village::create($validated);

        return redirect()->route('villages.index')
            ->with('success', 'Desa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Village $village)
    {
        return view('villages.show', compact('village'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Village $village)
    {
        return view('villages.edit', compact('village'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Village $village)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'akses' => 'required|in:DARAT,SEBERANG',
            'transport_standard' => 'required|numeric|min:0',
        ]);

        $village->update($validated);

        return redirect()->route('villages.index')
            ->with('success', 'Desa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Village $village)
    {
        $village->delete();

        return redirect()->route('villages.index')
            ->with('success', 'Desa berhasil dihapus.');
    }
}
