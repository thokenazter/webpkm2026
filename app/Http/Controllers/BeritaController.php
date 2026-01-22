<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Display list of published posts
     */
    public function index(Request $request)
    {
        $query = Post::with('category')->published()->latest('published_at');

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('kategori')) {
            $query->byCategory($request->kategori);
        }

        $posts = $query->paginate(9)->withQueryString();
        $categories = Category::berita()->withCount([
            'posts' => function ($q) {
                $q->published();
            }
        ])->get();

        return view('berita.index', compact('posts', 'categories'));
    }

    /**
     * Display single post
     */
    public function show(Post $post)
    {
        // Only show published posts
        if (!$post->is_published) {
            abort(404);
        }

        // Increment view count
        $post->incrementViews();

        // Get related posts
        $relatedPosts = Post::with('category')
            ->published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, function ($q) use ($post) {
                $q->where('category_id', $post->category_id);
            })
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('berita.show', compact('post', 'relatedPosts'));
    }

    /**
     * Real-time search API
     */
    public function search(Request $request)
    {
        $term = $request->get('q', '');

        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $posts = Post::published()
            ->search($term)
            ->latest('published_at')
            ->take(5)
            ->get(['id', 'title', 'slug', 'excerpt', 'published_at', 'created_at']);

        return response()->json($posts->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => \Illuminate\Support\Str::limit($post->excerpt, 80),
                'date' => $post->formatted_date,
                'url' => route('berita.show', $post->slug),
            ];
        }));
    }
}
