<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Redirect extends Model
{
    protected $fillable = [
        'from_path',
        'to_path',
        'status_code',
        'is_active',
        'hits',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'status_code' => 'integer',
        'hits'        => 'integer',
    ];

    // ── Scopes ─────────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
