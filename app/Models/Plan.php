<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'slug', 'name_ar', 'name_en', 'name_nl', 'name_de',
        'description_ar', 'description_en',
        'price_monthly', 'price_yearly', 'currency',
        'features', 'is_popular', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'features'   => 'array',
        'is_popular' => 'boolean',
        'is_active'  => 'boolean',
        'price_monthly' => 'decimal:2',
        'price_yearly'  => 'decimal:2',
    ];

    public function getName(string $lang = 'ar'): string
    {
        return $this->{"name_{$lang}"} ?? $this->name_ar ?? '';
    }

    public function getDescription(string $lang = 'ar'): string
    {
        return $this->{"description_{$lang}"} ?? $this->description_ar ?? '';
    }

    public function getFeatures(string $lang = 'ar'): array
    {
        $all = $this->features ?? [];
        return $all[$lang] ?? $all['ar'] ?? [];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
