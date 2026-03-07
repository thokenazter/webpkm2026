<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = Activity::latest()->paginate(10);
        return view('activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programs = \App\Models\Program::all();
        return view('activities.create', compact('programs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'name' => 'required|string|max:255',
            'target' => 'nullable|string|max:255',
            'schedule' => 'nullable|array',
            'pic' => 'nullable|string|max:255',
        ]);

        Activity::create($validated);

        return redirect()->route('activities.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        return view('activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        $programs = \App\Models\Program::all();
        return view('activities.edit', compact('activity', 'programs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'name' => 'required|string|max:255',
            'target' => 'nullable|string|max:255',
            'schedule' => 'nullable|array',
            'pic' => 'nullable|string|max:255',
        ]);

        $activity->update($validated);

        return redirect()->route('activities.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    /**
     * Show the form for managing the budget (RAB) of the specified activity.
     */
    public function budget(Activity $activity)
    {
        $activity->load('budgetItems');
        return view('activities.budget', compact('activity'));
    }

    /**
     * Update the budget items for the specified activity.
     */
    public function updateBudget(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'items' => 'array',
            'items.*.name' => 'required|string|max:255',
            'items.*.volume' => 'required|integer|min:1',
            'items.*.frequency' => 'required|integer|min:1',
            'items.*.unit' => 'required|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Delete existing items (simple approach for now, or sync)
        $activity->budgetItems()->delete();

        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $activity->budgetItems()->create([
                    'name' => $item['name'],
                    'volume' => $item['volume'],
                    'frequency' => $item['frequency'],
                    'unit' => $item['unit'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['volume'] * $item['frequency'] * $item['unit_price'],
                ]);
            }
        }

        return redirect()->route('activities.index')
            ->with('success', 'RAB berhasil diperbarui.');
    }
}
