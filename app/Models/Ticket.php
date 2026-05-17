<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number', 'subject', 'status', 'priority',
        'requester_name', 'requester_email', 'body', 'channel',
        'assigned_to', 'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $ticket) {
            if (empty($ticket->ticket_number)) {
                $next = (static::max('id') ?? 0) + 1;
                $ticket->ticket_number = 'TKT-' . str_pad($next, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }
}
