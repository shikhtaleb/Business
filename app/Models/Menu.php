<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    public const LOCATION_HEADER = 'header';
    public const LOCATION_FOOTER = 'footer';
    public const LOCATION_CUSTOM = 'custom';

    public const LOCATIONS = [
        self::LOCATION_HEADER => 'Header',
        self::LOCATION_FOOTER => 'Footer',
        self::LOCATION_CUSTOM => 'Custom',
    ];

    protected $fillable = [
        'name',
        'location',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    public function rootItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)
            ->whereNull('parent_id')
            ->orderBy('sort_order');
    }
}
