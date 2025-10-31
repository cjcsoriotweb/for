<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicketMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'is_support',
        'content',
        'read_at',
        'context_label',
        'context_path',
    ];

    protected $casts = [
        'is_support' => 'bool',
        'read_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $message): void {
            if (! $message->read_at && $message->is_support) {
                // support messages are immediately considered read for the sender
                $message->read_at = now();
            }
        });

        static::created(function (self $message): void {
            $ticket = $message->ticket;

            if (! $ticket) {
                return;
            }

            $ticket->forceFill([
                'last_message_at' => $message->created_at,
                'status' => $message->is_support
                    ? SupportTicket::STATUS_PENDING
                    : SupportTicket::STATUS_OPEN,
                'origin_label' => $ticket->origin_label ?: $message->context_label,
                'origin_path' => $message->context_path ?? $ticket->origin_path,
            ])->save();
        });
    }

    /**
     * Parent ticket.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    /**
     * Author of the message (optional for system notes).
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeForTicket($query, SupportTicket $ticket)
    {
        return $query->where('ticket_id', $ticket->id);
    }

    public function isFromOwner(): bool
    {
        return ! $this->is_support;
    }

    public function markAsRead(): void
    {
        if ($this->read_at) {
            return;
        }

        $this->forceFill([
            'read_at' => now(),
        ])->save();
    }
}
