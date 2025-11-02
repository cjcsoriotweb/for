<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiTrainer extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'description',
        'model',
        'temperature',
        'use_tools',
        'guard',
        'prompt_purpose',
        'prompt_allowed',
        'prompt_not_allowed',
        'prompt_rules',
        'prompt_custom',
        'is_active',
        'show_everywhere',
        'sort_order',
    ];

    protected $casts = [
        'use_tools' => 'bool',
        'is_active' => 'bool',
        'show_everywhere' => 'bool',
        'temperature' => 'float',
    ];

    /**
     * Scope active trainers.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Computed system prompt from stored sections.
     */
    public function systemPrompt(): string
    {
        if ($this->prompt_custom && trim($this->prompt_custom) !== '') {
            return trim($this->prompt_custom);
        }

        $sections = [];

        if ($this->prompt_purpose) {
            $sections[] = "Voici a quoi tu sers :\n" . trim($this->prompt_purpose);
        }

        if ($this->prompt_allowed) {
            $sections[] = "Voici ce que tu peux dire :\n" . trim($this->prompt_allowed);
        }

        if ($this->prompt_not_allowed) {
            $sections[] = "Voici ce que tu ne peux pas dire :\n" . trim($this->prompt_not_allowed);
        }

        if ($this->prompt_rules) {
            $sections[] = "Regles importantes :\n" . trim($this->prompt_rules);
        }

        $prompt = trim(implode("\n\n", array_filter($sections)));

        return $prompt !== '' ? $prompt : 'Tu es un assistant Evolubat.';
    }
}
