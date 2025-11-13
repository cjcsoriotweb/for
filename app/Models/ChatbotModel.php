<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatbotModel extends Model
{
    protected $fillable = [
        'key',
        'name',
        'image',
        'description',
    ];

    /**
     * @return HasMany<ChatbotConversation>
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(ChatbotConversation::class);
    }
}
