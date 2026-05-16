<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'name', 'email', 'subject', 'body', 'status', 'ip', 'lang',
        'replied_at', 'replied_by',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function replies(): HasMany
    {
        return $this->hasMany(MessageReply::class);
    }

    public function repliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function markRead(): void
    {
        if ($this->status === 'unread') {
            $this->update(['status' => 'read']);
        }
    }

    public static function unreadCount(): int
    {
        return static::where('status', 'unread')->count();
    }
}
