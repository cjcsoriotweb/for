<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
            ->withPivot(['status', 'progress_percent', 'current_lesson_id', 'enrolled_at', 'last_seen_at', 'completed_at', 'score_total', 'max_score_total'])
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
}
