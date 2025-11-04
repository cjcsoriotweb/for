<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IaChatMessage extends Model
{
    use HasFactory;

    public const ROLE_USER = 'user';
    public const ROLE_ASSISTANT = 'assistant';

    public const STATUS_PENDING = 'pending';
    public const STATUS_SEEN = 'vu';
    public const STATUS_COMPLETED = 'termine';
    public const STATUS_FAILED = 'echec';

    protected $fillable = [
        'user_id',
        'trainer_id',
        'parent_id',
        'role',
        'status',
        'content',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(AiTrainer::class, 'trainer_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function scopePendingUserMessages($query)
    {
        return $query->where('role', self::ROLE_USER)
            ->where('status', self::STATUS_PENDING);
    }
}
