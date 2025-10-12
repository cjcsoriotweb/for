<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'personal_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
        ];
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->profile_photo_path
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->profile_photo_path)
            : 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=0D8ABC&color=fff';
    }


    public function formations()
    {
        return $this->belongsToMany(\App\Models\Formation::class, 'formation_team')
            ->withPivot(['visible','approved_at','approved_by','starts_at','ends_at'])
            ->withTimestamps();
    }

}
