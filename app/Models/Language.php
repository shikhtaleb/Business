<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Language extends Model
{
    protected $fillable = [
        'code', 'name', 'native_name', 'flag_code',
        'is_rtl', 'is_active', 'is_default', 'translations', 'sort_order',
    ];

    protected $casts = [
        'is_rtl'      => 'boolean',
        'is_active'   => 'boolean',
        'is_default'  => 'boolean',
        'translations' => 'array',
    ];

    public static function active(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('active_languages', 3600, fn() =>
            static::where('is_active', true)->orderBy('sort_order')->get()
        );
    }

    public static function clearCache(): void
    {
        Cache::forget('active_languages');
    }

    public function setAsDefault(): void
    {
        static::where('is_default', true)->update(['is_default' => false]);
        $this->update(['is_default' => true]);
        static::clearCache();
    }
}
