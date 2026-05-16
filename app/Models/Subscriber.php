<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Subscriber extends Model
{
    protected $fillable = [
        'email', 'name', 'lang', 'status', 'source',
        'token', 'subscribed_at', 'unsubscribed_at',
    ];

    protected $casts = [
        'subscribed_at'   => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $subscriber) {
            if (empty($subscriber->token)) {
                $subscriber->token = Str::random(64);
            }
            if (empty($subscriber->subscribed_at)) {
                $subscriber->subscribed_at = now();
            }
        });
    }

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_subscribers')
            ->withPivot(['sent_at', 'opened_at'])
            ->withTimestamps();
    }
}
