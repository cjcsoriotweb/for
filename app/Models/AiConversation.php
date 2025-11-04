<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'formation_id',
        'team_id',
        'status',
        'last_message_at',
        'closed_at',
        'metadata',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'closed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public const STATUS_ACTIVE = 'active';

    public const STATUS_ARCHIVED = 'archived';

    // Relation avec AiTrainer supprimée - trainers sont maintenant dans config/ai.php

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class, 'formation_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(AiConversationMessage::class, 'conversation_id')
            ->orderBy('created_at');
    }

    public function scopeAwaitingAi(Builder $query): Builder
    {
        return $query->whereRaw(
            <<<SQL
            (
                SELECT role
                FROM ai_conversation_messages
                WHERE ai_conversation_messages.conversation_id = ai_conversations.id
                ORDER BY id DESC
                LIMIT 1
            ) = ?
            SQL,
            [AiConversationMessage::ROLE_USER]
        );
    }

    public function archive(): void
    {
        $this->forceFill([
            'status' => self::STATUS_ARCHIVED,
            'closed_at' => now(),
        ])->save();
    }

    public function reopen(): void
    {
        $this->forceFill([
            'status' => self::STATUS_ACTIVE,
            'closed_at' => null,
        ])->save();
    }
}
