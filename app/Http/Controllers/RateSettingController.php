<?php

namespace App\Http\Controllers;

use App\Models\RateSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RateSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rateSettings = RateSetting::latest()->paginate(10);
        return view('rate-settings.index', compact('rateSettings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rate-settings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:rate_settings',
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        RateSetting::create($validated);

        return redirect()->route('rate-settings.index')
            ->with('success', 'Pengaturan tarif berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RateSetting $rateSetting)
    {
        return view('rate-settings.show', compact('rateSetting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RateSetting $rateSetting)
    {
        return view('rate-settings.edit', compact('rateSetting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RateSetting $rateSetting)
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255', Rule::unique('rate_settings')->ignore($rateSetting)],
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $rateSetting->update($validated);

        return redirect()->route('rate-settings.index')
            ->with('success', 'Pengaturan tarif berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RateSetting $rateSetting)
    {
        $rateSetting->delete();

        return redirect()->route('rate-settings.index')
            ->with('success', 'Pengaturan tarif berhasil dihapus.');
    }
}
