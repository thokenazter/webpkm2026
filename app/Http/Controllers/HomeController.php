<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Gallery;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get latest 3 published posts
        $latestPosts = Post::where('is_published', true)
            ->latest()
            ->take(3)
            ->get();

        // Get latest 8 galleries
        $latestGalleries = Gallery::latest()
            ->take(8)
            ->get();

        return view('home', compact('latestPosts', 'latestGalleries'));
    }
}
