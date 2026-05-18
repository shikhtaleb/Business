<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'title_ar',
        'title_en',
        'title_nl',
        'title_de',
        'body_ar',
        'body_en',
        'body_nl',
        'body_de',
        'blocks',
        'status',
        'template',
        'show_in_nav',
        'meta_title',
        'meta_desc',
        'author_id',
        'sort_order',
    ];

    protected $casts = [
        'show_in_nav' => 'boolean',
        'blocks'      => 'array',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // ── Scopes ─────────────────────────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    // ── Accessors ──────────────────────────────────────────────────────────────

    public function getTitle(string $lang = 'ar'): string
    {
        $field = 'title_' . $lang;
        return $this->{$field} ?? $this->title_ar ?? '';
    }

    public function getBody(string $lang = 'ar'): ?string
    {
        $field = 'body_' . $lang;
        return $this->{$field} ?? $this->body_ar;
    }
}
