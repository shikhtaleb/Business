<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    protected $fillable = [
        'name',
        'key_prefix',
        'key_hash',
        'user_id',
        'permissions',
        'last_used_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'permissions'  => 'array',
        'is_active'    => 'boolean',
        'last_used_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // ── Business Logic ─────────────────────────────────────────────────────────

    /**
     * Generate a new API key.
     * Returns ['key' => $plainTextKey, 'model' => ApiKey].
     * The plain text key is shown ONCE and never stored.
     */
    public static function generate(int $userId, string $name, array $permissions = [], ?\Carbon\Carbon $expiresAt = null): array
    {
        // 40-char random key with a readable prefix
        $plain  = 'rb_' . Str::random(37); // total: 40 chars
        $prefix = substr($plain, 0, 8);
        $hash   = hash('sha256', $plain);

        $model = static::create([
            'name'        => $name,
            'key_prefix'  => $prefix,
            'key_hash'    => $hash,
            'user_id'     => $userId,
            'permissions' => $permissions ?: null,
            'expires_at'  => $expiresAt,
            'is_active'   => true,
        ]);

        return ['key' => $plain, 'model' => $model];
    }

    /**
     * Verify a plain-text key against the stored hash.
     */
    public static function findByKey(string $plainKey): ?static
    {
        return static::where('key_hash', hash('sha256', $plainKey))->first();
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return $this->is_active && ! $this->isExpired();
    }
}
