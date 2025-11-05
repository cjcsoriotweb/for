<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function formations()
    {
        return $this->belongsToMany(Formation::class, 'formation_user')
            ->withPivot(['status', 'current_lesson_id', 'enrolled_at', 'last_seen_at', 'completed_at', 'score_total', 'max_score_total'])
            ->withTimestamps();
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user')
            ->withPivot(['status', 'watched_seconds', 'best_score', 'max_score', 'attempts', 'read_percent', 'started_at', 'last_activity_at', 'completed_at'])
            ->withTimestamps();
    }

    public function superadmin(): bool
    {
        return $this->superadmin;
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class, 'user_id');
    }

    public function supportTicketMessages()
    {
        return $this->hasMany(SupportTicketMessage::class, 'user_id');
    }

    public function claims()
    {
        return $this->hasMany(Claim::class, 'user_id');
    }

    public function claimUpdates()
    {
        return $this->hasMany(ClaimUpdate::class, 'user_id');
    }

    public function formationCategories()
    {
        return $this->hasMany(FormationCategory::class, 'created_by');
    }

    public function aiConversations()
    {
        return $this->hasMany(AiConversation::class, 'user_id');
    }

    public function sentChats()
    {
        return $this->hasMany(Chat::class, 'sender_user_id');
    }

    public function receivedChats()
    {
        return $this->hasMany(Chat::class, 'receiver_user_id');
    }



    /**
     * Provide a textual context summary for AI prompts.
     */
    public function getIaContext(): string
    {
        $relations = ['currentTeam', 'teams', 'ownedTeams', 'formations'];
        if (method_exists($this, 'roles')) {
            $relations[] = 'roles';
        }

        $this->loadMissing($relations);

        $segments = [];

        $segments[] = sprintf(
            'Utilisateur connecte : %s (%s).',
            $this->name ?? 'Inconnu',
            $this->email ?? 'Email inconnu'
        );

        if ($this->currentTeam) {
            $segments[] = sprintf(
                'Equipe active : %s.',
                $this->currentTeam->name
            );
        }

        $allTeams = $this->allTeams()
            ->pluck('name')
            ->filter()
            ->unique()
            ->values();

        if ($allTeams->isNotEmpty()) {
            $segments[] = 'Equipes accessibles :';
            foreach ($allTeams as $teamName) {
                $segments[] = sprintf('- %s', $teamName);
            }
        }

        $formations = $this->formations()
            ->select('formations.id', 'formations.title')
            ->withPivot(['status', 'enrolled_at', 'completed_at'])
            ->orderBy('formations.title')
            ->get();

        if ($formations->isNotEmpty()) {
            $segments[] = 'Formations associees :';
            foreach ($formations as $formation) {
                $status = $formation->pivot?->status ?: 'inconnu';
                $segments[] = sprintf(
                    '- %s (ID : %d, statut : %s)',
                    $formation->title,
                    $formation->id,
                    Str::ucfirst($status)
                );
            }
        }

        $tickets = $this->supportTickets()
            ->whereIn('status', [SupportTicket::STATUS_OPEN, SupportTicket::STATUS_PENDING])
            ->with(['messages' => function ($query) {
                $query->latest('id')->take(1);
            }])
            ->orderByDesc('last_message_at')
            ->limit(5)
            ->get();

        if ($tickets->isNotEmpty()) {
            $segments[] = 'Tickets support actifs :';
            foreach ($tickets as $ticket) {
                $lastMessage = $ticket->messages->first();
                $actor = $lastMessage ? ($lastMessage->is_support ? 'Support' : 'Utilisateur') : 'Aucun echange';
                $lastAt = optional($ticket->last_message_at)->diffForHumans() ?? 'inconnue';
                $subject = $ticket->subject ?: 'Sans objet';

                $segments[] = sprintf(
                    '- #%d "%s" (statut : %s, dernière réponse : %s - %s)',
                    $ticket->id,
                    $subject,
                    Str::ucfirst($ticket->status ?? 'inconnu'),
                    $actor,
                    $lastAt
                );
            }
        }

        $roles = method_exists($this, 'roles') ? $this->roles->pluck('name')->filter()->unique()->values() : collect();

        if ($roles instanceof Collection && $roles->isNotEmpty()) {
            $segments[] = 'Roles applicatifs :';
            foreach ($roles as $role) {
                $segments[] = sprintf('- %s', Str::ucfirst($role));
            }
        }

        return trim(collect($segments)->filter()->implode("\n"));
    }
}
