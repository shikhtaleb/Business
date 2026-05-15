<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentBlock extends Model
{
    protected $fillable = ['section', 'key', 'lang', 'value', 'type'];

    public static function get(string $section, string $key, string $lang, string $default = ''): string
    {
        $block = static::where('section', $section)
            ->where('key', $key)
            ->where('lang', $lang)
            ->first();

        return $block ? (string) $block->value : $default;
    }

    public static function getSection(string $section, string $lang): array
    {
        return static::where('section', $section)
            ->where('lang', $lang)
            ->pluck('value', 'key')
            ->toArray();
    }

    public static function upsert(string $section, string $key, string $lang, string $value, string $type = 'text'): void
    {
        static::updateOrCreate(
            ['section' => $section, 'key' => $key, 'lang' => $lang],
            ['value' => $value, 'type' => $type]
        );
    }
}
