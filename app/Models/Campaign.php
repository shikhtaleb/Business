<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Campaign extends Model
{
    protected $fillable = [
        'name', 'subject_ar', 'subject_en', 'body_ar', 'body_en',
        'status', 'target_lang', 'sent_at', 'sent_count', 'open_count',
    ];

    protected $casts = [
        'sent_at'    => 'datetime',
        'sent_count' => 'integer',
        'open_count' => 'integer',
    ];

    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(Subscriber::class, 'campaign_subscribers')
            ->withPivot(['sent_at', 'opened_at'])
            ->withTimestamps();
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeSent(Builder $query): Builder
    {
        return $query->where('status', 'sent');
    }
}
