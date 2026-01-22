<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MessageController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/struktur-organisasi', function () {
    return view('profil.struktur-organisasi');
});

Route::get('/klaster-1', function () {
    return view('klaster.klaster1');
});

Route::get('/klaster-2', function () {
    return view('klaster.klaster2');
});

Route::get('/klaster-3', function () {
    return view('klaster.klaster3');
});

Route::get('/klaster-4', function () {
    return view('klaster.klaster4');
});

Route::get('/lintas-klaster', function () {
    return view('klaster.lintas');
});

// Public Berita & Galeri Routes
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{post:slug}', [BeritaController::class, 'show'])->name('berita.show');
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');
Route::get('/api/search', [BeritaController::class, 'search'])->name('api.search');

// Contact Page & Form Route
Route::get('/hubungi-kami', function () {
    return view('hubungi-kami');
})->name('hubungi-kami');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});

// Admin Protected Routes
Route::prefix('admin')->middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('posts', PostController::class)->names('admin.posts');
    Route::resource('galleries', GalleryController::class)->names('admin.galleries');
    Route::resource('categories', CategoryController::class)->names('admin.categories');
    Route::get('messages', [MessageController::class, 'index'])->name('admin.messages.index');
    Route::get('messages/{message}', [MessageController::class, 'show'])->name('admin.messages.show');
    Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('admin.messages.destroy');
});
