<?php

namespace App\Http\Controllers;

use App\Models\AnnualBudget;
use Illuminate\Http\Request;

class AnnualBudgetController extends Controller
{
    public function index()
    {
        $budgets = AnnualBudget::latest()->paginate(10);
        return view('budgets.index', compact('budgets'));
    }

    public function create()
    {
        return view('budgets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);
        AnnualBudget::create($validated);
        return redirect()->route('budgets.index')->with('success', 'Pagu tahunan ditambahkan.');
    }

    public function show(AnnualBudget $budget)
    {
        $budget->load('allocations.rab');
        return view('budgets.show', compact('budget'));
    }

    public function edit(AnnualBudget $budget)
    {
        return view('budgets.edit', compact('budget'));
    }

    public function update(Request $request, AnnualBudget $budget)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);
        $budget->update($validated);
        return redirect()->route('budgets.index')->with('success', 'Pagu tahunan diperbarui.');
    }

    public function destroy(AnnualBudget $budget)
    {
        $budget->delete();
        return redirect()->route('budgets.index')->with('success', 'Pagu tahunan dihapus.');
    }
}

