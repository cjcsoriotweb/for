<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'ai_trainer_id',
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

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(AiTrainer::class, 'ai_trainer_id');
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
