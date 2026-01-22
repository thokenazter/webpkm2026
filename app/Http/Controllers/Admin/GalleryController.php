<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of galleries
     */
    public function index(Request $request)
    {
        $query = Gallery::with('category')->ordered();

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $galleries = $query->paginate(12)->withQueryString();
        $categories = Category::galeri()->get();

        return view('admin.galleries.index', compact('galleries', 'categories'));
    }

    /**
     * Show the form for creating a new gallery
     */
    public function create()
    {
        $categories = Category::galeri()->get();
        return view('admin.galleries.create', compact('categories'));
    }

    /**
     * Store a newly created gallery
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'event_date' => 'nullable|date',
            'is_published' => 'boolean',
        ]);

        // Handle image upload
        $validated['image_path'] = $request->file('image')->store('galleries', 'public');
        $validated['is_published'] = $request->boolean('is_published', true);

        // Get next sort order
        $validated['sort_order'] = Gallery::max('sort_order') + 1;

        Gallery::create($validated);

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Foto berhasil diupload!');
    }

    /**
     * Show the form for editing the specified gallery
     */
    public function edit(Gallery $gallery)
    {
        $categories = Category::galeri()->get();
        return view('admin.galleries.edit', compact('gallery', 'categories'));
    }

    /**
     * Update the specified gallery
     */
    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'event_date' => 'nullable|date',
            'is_published' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            Storage::disk('public')->delete($gallery->image_path);
            $validated['image_path'] = $request->file('image')->store('galleries', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published', true);

        $gallery->update($validated);

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Foto berhasil diperbarui!');
    }

    /**
     * Remove the specified gallery
     */
    public function destroy(Gallery $gallery)
    {
        // Delete image
        Storage::disk('public')->delete($gallery->image_path);

        $gallery->delete();

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Foto berhasil dihapus!');
    }
}
