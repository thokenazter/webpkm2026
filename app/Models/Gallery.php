<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'image_path',
        'event_date',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'event_date' => 'date',
        'sort_order' => 'integer',
    ];

    /**
     * Get the category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope for published galleries
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, $categorySlug)
    {
        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    /**
     * Get thumbnail URL (for grid display)
     */
    public function getThumbnailUrlAttribute(): string
    {
        // If using intervention/image for thumbnails, modify path
        // For now, return same as image
        return $this->image_url;
    }

    /**
     * Get formatted event date
     */
    public function getFormattedDateAttribute(): ?string
    {
        return $this->event_date
            ? $this->event_date->translatedFormat('d F Y')
            : null;
    }
}
