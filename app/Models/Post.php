<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

class Post extends Model
{
    protected $fillable = [
        'slug',
        'title_ar',
        'title_en',
        'title_nl',
        'title_de',
        'excerpt_ar',
        'excerpt_en',
        'excerpt_nl',
        'excerpt_de',
        'body_ar',
        'body_en',
        'body_nl',
        'body_de',
        'category_id',
        'author_id',
        'featured_image',
        'status',
        'published_at',
        'is_featured',
        'views_count',
        'seo_title',
        'seo_desc',
        'lang_locked',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured'  => 'boolean',
            'lang_locked'  => 'boolean',
            'status'       => 'string',
            'views_count'  => 'integer',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Return the post title in the requested language,
     * falling back to Arabic if the translation is missing.
     */
    public function getTitle(string $lang = 'ar'): string
    {
        $field = 'title_' . $lang;

        return (string) ($this->{$field} ?? $this->title_ar ?? '');
    }

    /**
     * Return the post body in the requested language,
     * falling back to Arabic if the translation is missing.
     */
    public function getBody(string $lang = 'ar'): string
    {
        $field = 'body_' . $lang;

        return (string) ($this->{$field} ?? $this->body_ar ?? '');
    }

    /**
     * Return the post excerpt in the requested language,
     * falling back to Arabic if the translation is missing.
     */
    public function getExcerpt(string $lang = 'ar'): string
    {
        $field = 'excerpt_' . $lang;

        return (string) ($this->{$field} ?? $this->excerpt_ar ?? '');
    }

    /**
     * Check whether the post is currently publicly visible.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published'
            && $this->published_at !== null
            && $this->published_at->lte(Carbon::now());
    }

    /**
     * Increment the view counter by 1.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    /**
     * Only published posts whose published_at is in the past (or now).
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', Carbon::now());
    }

    /**
     * Only featured posts.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
