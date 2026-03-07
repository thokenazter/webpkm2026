<?php

namespace App\Http\Controllers\Pkm\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount(['posts', 'galleries']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $categories = $query->orderBy('type')->orderBy('name')->get();

        return view('pkm.admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('pkm.admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:berita,galeri',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $count = Category::where('slug', 'like', $validated['slug'] . '%')->count();
        if ($count > 0) {
            $validated['slug'] .= '-' . ($count + 1);
        }

        Category::create($validated);

        return redirect()->route('pkm-admin.categories.index')
            ->with('success', 'Kategori berhasil dibuat!');
    }

    public function edit(Category $category)
    {
        return view('pkm.admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:berita,galeri',
            'description' => 'nullable|string|max:500',
        ]);

        if ($category->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
            $count = Category::where('slug', 'like', $validated['slug'] . '%')
                ->where('id', '!=', $category->id)
                ->count();
            if ($count > 0) {
                $validated['slug'] .= '-' . ($count + 1);
            }
        }

        $category->update($validated);

        return redirect()->route('pkm-admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        if ($category->posts()->count() > 0 || $category->galleries()->count() > 0) {
            return redirect()->route('pkm-admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan!');
        }

        $category->delete();

        return redirect()->route('pkm-admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
