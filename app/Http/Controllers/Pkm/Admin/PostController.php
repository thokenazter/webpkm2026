<?php

namespace App\Http\Controllers\Pkm\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('category')->latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $posts = $query->paginate(10)->withQueryString();
        $categories = Category::berita()->get();

        return view('pkm.admin.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = Category::berita()->get();
        return view('pkm.admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'author' => 'nullable|string|max:255',
        ];

        if ($request->hasFile('featured_image')) {
            $rules['featured_image'] = 'image|mimes:jpeg,png,jpg,webp|max:10240';
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);
        $count = Post::where('slug', 'like', $validated['slug'] . '%')->count();
        if ($count > 0) {
            $validated['slug'] .= '-' . ($count + 1);
        }

        if ($request->boolean('is_published')) {
            $validated['published_at'] = now();
        }

        if (empty($validated['author'])) {
            $validated['author'] = auth()->user()->name;
        }

        Post::create($validated);

        return redirect()->route('pkm-admin.posts.index')
            ->with('success', 'Berita berhasil dibuat!');
    }

    public function edit(Post $post)
    {
        $categories = Category::berita()->get();
        return view('pkm.admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'author' => 'nullable|string|max:255',
        ];

        if ($request->hasFile('featured_image')) {
            $rules['featured_image'] = 'image|mimes:jpeg,png,jpg,webp|max:10240';
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        if ($post->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
            $count = Post::where('slug', 'like', $validated['slug'] . '%')
                ->where('id', '!=', $post->id)
                ->count();
            if ($count > 0) {
                $validated['slug'] .= '-' . ($count + 1);
            }
        }

        if ($request->boolean('is_published') && !$post->is_published) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        return redirect()->route('pkm-admin.posts.index')
            ->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy(Post $post)
    {
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('pkm-admin.posts.index')
            ->with('success', 'Berita berhasil dihapus!');
    }
}
