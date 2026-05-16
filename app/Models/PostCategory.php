<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostCategory extends Model
{
    protected $fillable = [
        'slug',
        'name_ar',
        'name_en',
        'name_nl',
        'name_de',
        'parent_id',
        'sort_order',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(PostCategory::class, 'parent_id')->orderBy('sort_order');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Return the category name in the requested language,
     * falling back to Arabic if the translation is missing.
     */
    public function getName(string $lang = 'ar'): string
    {
        $field = 'name_' . $lang;

        return (string) ($this->{$field} ?? $this->name_ar ?? '');
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    /**
     * Only root-level categories (no parent).
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }
}
