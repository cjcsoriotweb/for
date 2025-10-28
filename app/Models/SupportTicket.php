<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'status',
        'last_message_at',
        'resolved_at',
        'closed_at',
        'closed_by',
        'origin_label',
        'origin_path',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public const STATUS_OPEN = 'open';
    public const STATUS_PENDING = 'pending';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CLOSED = 'closed';

    /**
     * Ticket owner.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Support agent that closed the ticket (optional).
     */
    public function closer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    /**
     * Messages attached to the ticket.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(SupportTicketMessage::class, 'ticket_id')
            ->orderBy('created_at');
    }

    public function markAsPending(): void
    {
        $this->update([
            'status' => self::STATUS_PENDING,
        ]);
    }

    public function markAsResolved(?User $user = null): void
    {
        $this->update([
            'status' => self::STATUS_RESOLVED,
            'resolved_at' => now(),
            'closed_at' => null,
            'closed_by' => optional($user)->id,
        ]);
    }

    public function close(?User $user = null): void
    {
        $this->update([
            'status' => self::STATUS_CLOSED,
            'closed_at' => now(),
            'closed_by' => optional($user)->id,
        ]);
    }

    public function reopen(): void
    {
        $this->update([
            'status' => self::STATUS_OPEN,
            'closed_at' => null,
            'closed_by' => null,
        ]);
    }
}
