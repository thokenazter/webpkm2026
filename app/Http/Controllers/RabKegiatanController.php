<?php

namespace App\Http\Controllers;

use App\Models\RabKegiatan;
use App\Models\Rab;
use App\Models\RabMenu;
use Illuminate\Http\Request;

class RabKegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = RabKegiatan::with('menu')->latest()->paginate(10);
        return view('rab-kegiatans.index', compact('kegiatans'));
    }

    public function create(Request $request)
    {
        $menus = RabMenu::orderBy('name')->get();
        $selectedMenuId = (int) $request->query('rab_menu_id');
        $selectedMenu = $selectedMenuId ? RabMenu::find($selectedMenuId) : null;
        return view('rab-kegiatans.create', compact('menus', 'selectedMenuId', 'selectedMenu'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rab_menu_id' => ['required','exists:rab_menus,id'],
            'names' => ['required','array','min:1'],
            'names.*' => ['required','string','max:255'],
        ]);

        $menuId = (int) $validated['rab_menu_id'];
        $names = collect($validated['names'])
            ->map(fn($n) => trim($n))
            ->filter();

        $created = 0;
        $newKegiatan = null;
        foreach ($names as $name) {
            // Avoid duplicate by same menu + name
            $exists = RabKegiatan::where('rab_menu_id', $menuId)->where('name', $name)->exists();
            if ($exists) continue;
            $kegiatan = RabKegiatan::create([
                'rab_menu_id' => $menuId,
                'name' => $name,
            ]);
            $created++;
            if (!$newKegiatan) {
                $newKegiatan = $kegiatan; // Store the first created kegiatan
            }
        }

        $message = $created > 1 ? "$created kegiatan ditambahkan." : ($created === 1 ? '1 kegiatan ditambahkan.' : 'Tidak ada kegiatan baru ditambahkan (duplikat).');

        // If request expects JSON (for inline creation)
        if ($request->expectsJson()) {
            if ($created > 0 && $newKegiatan) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kegiatan berhasil ditambahkan.',
                    'kegiatan_id' => $newKegiatan->id,
                    'kegiatan_name' => $newKegiatan->name
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada kegiatan baru ditambahkan (duplikat).'
                ], 422);
            }
        }

        if ($request->boolean('add_more')) {
            return redirect()->route('rab-kegiatans.create', ['rab_menu_id' => $menuId])
                ->with('success', $message . ' Tambah lagi jika perlu.');
        }

        return redirect()->route('rab-kegiatans.index')->with('success', $message);
    }

    public function show(RabKegiatan $rab_kegiatan)
    {
        return view('rab-kegiatans.show', ['kegiatan' => $rab_kegiatan->load('menu')]);
    }

    public function edit(RabKegiatan $rab_kegiatan)
    {
        $menus = RabMenu::orderBy('name')->get();
        return view('rab-kegiatans.edit', ['kegiatan' => $rab_kegiatan, 'menus' => $menus]);
    }

    public function update(Request $request, RabKegiatan $rab_kegiatan)
    {
        $validated = $request->validate([
            'rab_menu_id' => ['required','exists:rab_menus,id'],
            'name' => ['required','string','max:255'],
        ]);
        $rab_kegiatan->update($validated);
        return redirect()->route('rab-kegiatans.index')->with('success', 'Kegiatan diperbarui.');
    }

    public function destroy(RabKegiatan $rab_kegiatan)
    {
        $rab_kegiatan->delete();
        return redirect()->route('rab-kegiatans.index')->with('success', 'Kegiatan dihapus.');
    }

    // API: by rab_menu_id
    public function byMenu(Request $request)
    {
        $menuId = $request->query('rab_menu_id');
        if (!$menuId) return response()->json(['data' => []]);
        // Exclude kegiatan that already have a RAB linked via rab_kegiatan_id
        $usedIds = Rab::whereNotNull('rab_kegiatan_id')->pluck('rab_kegiatan_id');
        $data = RabKegiatan::where('rab_menu_id', $menuId)
            ->whereNotIn('id', $usedIds)
            ->orderBy('name')
            ->get(['id','name']);
        return response()->json(['data' => $data]);
    }
}
