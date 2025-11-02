<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'ai_trainer_id',
        'created_by',
    ];

    public function aiTrainer(): BelongsTo
    {
        return $this->belongsTo(AiTrainer::class, 'ai_trainer_id');
    }

    public function formations(): HasMany
    {
        return $this->hasMany(Formation::class, 'formation_category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
