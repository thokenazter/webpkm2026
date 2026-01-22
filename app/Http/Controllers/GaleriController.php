<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Category;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    /**
     * Display list of published galleries
     */
    public function index(Request $request)
    {
        $query = Gallery::with('category')->published()->ordered();

        // Filter by category
        if ($request->filled('kategori')) {
            $query->byCategory($request->kategori);
        }

        $galleries = $query->paginate(12)->withQueryString();
        $categories = Category::galeri()->withCount([
            'galleries' => function ($q) {
                $q->published();
            }
        ])->get();

        return view('galeri.index', compact('galleries', 'categories'));
    }
}
