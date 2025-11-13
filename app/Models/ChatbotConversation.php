<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatbotConversation extends Model
{
    protected $fillable = [
        'user_id',
        'chatbot_model_id',
    ];

    /**
     * @return BelongsTo<ChatbotModel, ChatbotConversation>
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(ChatbotModel::class, 'chatbot_model_id');
    }

    /**
     * @return HasMany<ChatWithBot>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatWithBot::class, 'chatbot_conversation_id');
    }
}
