<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiConversationMessage extends Model
{
    use HasFactory;

    public const ROLE_USER = 'user';

    public const ROLE_ASSISTANT = 'assistant';

    public const ROLE_SYSTEM = 'system';

    protected $fillable = [
        'conversation_id',
        'user_id',
        'role',
        'content',
        'context_label',
        'context_path',
        'prompt_tokens',
        'completion_tokens',
        'metadata',
    ];

    protected $casts = [
        'prompt_tokens' => 'integer',
        'completion_tokens' => 'integer',
        'metadata' => 'array',
    ];

    protected static function booted(): void
    {
        static::created(function (self $message): void {
            $conversation = $message->conversation;

            if (! $conversation) {
                return;
            }

            $conversation->forceFill([
                'last_message_at' => $message->created_at,
            ])->save();
        });
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AiConversation::class, 'conversation_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
