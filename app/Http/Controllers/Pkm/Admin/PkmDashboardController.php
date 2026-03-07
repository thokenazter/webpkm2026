<?php

namespace App\Http\Controllers\Pkm\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Gallery;
use App\Models\Category;

class PkmDashboardController extends Controller
{
    /**
     * Show dashboard
     */
    public function index()
    {
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::published()->count(),
            'total_galleries' => Gallery::count(),
            'total_views' => Post::sum('views_count'),
        ];

        $recentPosts = Post::with('category')
            ->latest()
            ->take(5)
            ->get();

        $recentGalleries = Gallery::with('category')
            ->latest()
            ->take(6)
            ->get();

        return view('pkm.admin.dashboard', compact('stats', 'recentPosts', 'recentGalleries'));
    }
}
