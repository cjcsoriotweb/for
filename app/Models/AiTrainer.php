<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class AiTrainer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'provider',
        'model',
        'description',
        'prompt',
        'avatar_path',
        'is_default',
        'is_active',
        'settings',
        'last_tested_at',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'settings' => 'array',
        'last_tested_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $trainer): void {
            if (empty($trainer->slug)) {
                $trainer->slug = Str::slug($trainer->name) ?: Str::uuid()->toString();
            }
        });

        static::saving(function (self $trainer): void {
            if ($trainer->is_default) {
                static::whereKeyNot($trainer->getKey() ?: 0)
                    ->update(['is_default' => false]);
            }
        });
    }

    public function formations(): BelongsToMany
    {
        return $this->belongsToMany(Formation::class, 'ai_trainer_formation')
            ->withPivot(['is_primary'])
            ->withTimestamps();
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(AiConversation::class, 'ai_trainer_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true)->limit(1);
    }
}
