<?php

namespace App\Http\Controllers\Pkm;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Gallery;
use Illuminate\Http\Request;

class PkmHomeController extends Controller
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

        return view('pkm.home', compact('latestPosts', 'latestGalleries'));
    }
}
