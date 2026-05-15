<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    private static int $cacheTtl = 3600;

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = static::getAllCached();
        return $all[$key] ?? $default;
    }

    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
        static::clearCache();
    }

    public static function getGroup(string $group): array
    {
        return static::where('group', $group)->pluck('value', 'key')->toArray();
    }

    public static function clearCache(): void
    {
        Cache::forget('cms_settings_all');
    }

    private static function getAllCached(): array
    {
        return Cache::remember('cms_settings_all', static::$cacheTtl, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }
}
