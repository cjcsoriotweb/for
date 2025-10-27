<?php

namespace App\Models;

use App\Models\FormationCompletionDocument;
use Database\Factories\FormationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    /**
     * Alias for completionDocuments to support scoped route bindings (documents/{document}).
     */
    public function documents(): HasMany
    {
        return $this->completionDocuments();
    }

    /*
    public function team()
    {
        return $this->belongsTo(Team::class);
    }




    public function getFormationUserAttribute()
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        return $this->learners()
            ->where('users.id', $user->id)
            ->first()?->pivot;
    }

    public function progressTarget()
    {
        // Cible de progression par défaut - peut être configuré dynamiquement
        return 80;
    }




    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'formation_in_teams')
            ->withTimestamps();
    }

    public function scopeForTeam(Builder $query, int|Team $team): Builder
    {
        $teamId = $team instanceof Team ? $team->id : $team;

        return $query->whereHas('teams', function ($q) use ($teamId) {
            $q->where('teams.id', $teamId)
            ->where('formation_teams.visible', 1); // filtre pivot
            // ou: ->wherePivot('visible', true);  // fonctionne aussi sur BelongsToMany
        });
    }

    public function scopeAdminWithTeamLink(Builder $query, int|Team $team): Builder
    {
        $teamId = $team instanceof Team ? $team->id : $team;

        return $query
            ->leftJoin('formation_teams as ft', function ($join) use ($teamId) {
                $join->on('ft.formation_id', '=', 'formations.id')
                    ->where('ft.team_id', '=', $teamId);
            })
            ->select('formations.*')
            ->addSelect([
                DB::raw('CASE WHEN ft.formation_id IS NULL THEN 0 ELSE 1 END AS is_linked'),
                DB::raw('ft.id AS pivot_id'),
                DB::raw('ft.team_id AS pivot_team_id'),
                DB::raw('ft.visible AS pivot_active'),   // adapte si tu as ce champ
                // DB::raw('ft.visible_at AS pivot_visible_at'),
            ]);
    }
    */
}
