<?php

namespace App\Http\Controllers;

use App\Models\RabMenu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RabMenuController extends Controller
{
    public function index()
    {
        $menus = RabMenu::latest()->paginate(10);
        return view('rab-menus.index', compact('menus'));
    }

    public function create()
    {
        $components = \App\Models\Rab::components();
        return view('rab-menus.create', compact('components'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'component_key' => ['required','string', Rule::in(array_keys(\App\Models\Rab::components()))],
            'name' => ['required','string','max:255'],
        ]);

        $menu = RabMenu::create($validated);

        // If request expects JSON (for inline creation)
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Rincian Menu berhasil ditambahkan.',
                'menu' => $menu
            ]);
        }

        // After creating a Rincian Menu, continue to add Kegiatan for this menu
        return redirect()->route('rab-kegiatans.create', ['rab_menu_id' => $menu->id])
            ->with('success', 'Rincian Menu ditambahkan. Lanjut isi Kegiatan untuk menu ini.');
    }

    public function show(RabMenu $rab_menu)
    {
        return view('rab-menus.show', ['menu' => $rab_menu]);
    }

    public function edit(RabMenu $rab_menu)
    {
        $components = \App\Models\Rab::components();
        return view('rab-menus.edit', ['menu' => $rab_menu, 'components' => $components]);
    }

    public function update(Request $request, RabMenu $rab_menu)
    {
        $validated = $request->validate([
            'component_key' => ['required','string', Rule::in(array_keys(\App\Models\Rab::components()))],
            'name' => ['required','string','max:255'],
        ]);
        $rab_menu->update($validated);
        return redirect()->route('rab-menus.index')->with('success', 'Rincian Menu diperbarui.');
    }

    public function destroy(RabMenu $rab_menu)
    {
        $rab_menu->delete();
        return redirect()->route('rab-menus.index')->with('success', 'Rincian Menu dihapus.');
    }

    // API: by component key
    public function byComponent(Request $request)
    {
        $component = $request->query('component_key');
        if (!$component) return response()->json(['data' => []]);
        $data = RabMenu::where('component_key', $component)
            ->orderBy('name')
            ->get(['id','name']);
        return response()->json(['data' => $data]);
    }
}
