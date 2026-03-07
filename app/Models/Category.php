<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
    ];

    /**
     * Get posts in this category
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get galleries in this category
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    /**
     * Scope for berita categories
     */
    public function scopeBerita($query)
    {
        return $query->where('type', 'berita');
    }

    /**
     * Scope for galeri categories
     */
    public function scopeGaleri($query)
    {
        return $query->where('type', 'galeri');
    }

    /**
     * Get route key name for URL binding
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
