<?php

namespace App\Models;

use Database\Factories\FormationFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

/**
 * Modèle Formation
 *
 * Représente une formation dans le système d'apprentissage.
 * Une formation appartient à une équipe propriétaire et peut être visible pour d'autres équipes.
 */
class Formation extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected static function newFactory()
    {
        return FormationFactory::new();
    }

    protected $fillable = [
        'title',
        'description',
        'level',
        'money_amount',
        'active',
        'cover_image_path',
        'user_id',
        'formation_category_id',
    ];

    protected function casts(): array
    {
        return [
            'title' => 'string',
            'description' => 'string',
            'level' => 'string',
            'money_amount' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'formation_in_teams')
            ->withPivot(['visible', 'approved_at', 'approved_by'])
            ->withTimestamps();
    }

    public function lessons()
    {
        // Formation (id) -> Chapters (formation_id) -> Lessons (chapter_id)
        return $this->hasManyThrough(
            Lesson::class,     // final
            Chapter::class,    // intermédiaire
            'formation_id',    // clé étrangère sur chapters -> formations.id
            'chapter_id',      // clé étrangère sur lessons -> chapters.id
            'id',              // clé locale formations.id
            'id'               // clé locale chapters.id
        );
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('position');
    }

    public function learners() // utilisateurs inscrits
    {
        return $this->belongsToMany(User::class, 'formation_user')
            ->withPivot([
                'status',
                'current_lesson_id',
                'enrolled_at',
                'last_seen_at',
                'completed_at',
                'score_total',
                'max_score_total',
                'entry_quiz_attempt_id',
                'entry_quiz_score',
                'entry_quiz_completed_at',
                'post_quiz_attempt_id',
                'post_quiz_score',
                'post_quiz_completed_at',
                'quiz_progress_delta',
                'enrollment_cost',
            ])
            ->withTimestamps();
    }

    /**
     * Alias for learners() method for backward compatibility
     */
    public function students()
    {
        return $this->learners();
    }

    public function completionDocuments(): HasMany
    {
        return $this->hasMany(FormationCompletionDocument::class);
    }

    public function entryQuiz(): HasOne
    {
        return $this->hasOne(Quiz::class)
            ->where('type', Quiz::TYPE_ENTRY);
    }

    public function questions()
    {
        return $this->entryQuiz()->first()?->quizQuestions() ?? collect();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FormationCategory::class, 'formation_category_id');
    }

    public function aiTrainer(): ?AiTrainer
    {
        return $this->category?->aiTrainer;
    }

    /**
     * Alias for completionDocuments to support scoped route bindings (documents/{document}).
     */
    public function documents(): HasMany
    {
        return $this->completionDocuments();
    }

    public function getCoverImageUrlAttribute(): string
    {
        if ($this->cover_image_path && Storage::disk('public')->exists($this->cover_image_path)) {
            return Storage::disk('public')->url($this->cover_image_path);
        }

        return asset('images/formation-placeholder.svg');
    }
}
