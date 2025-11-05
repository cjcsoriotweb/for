<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormationUser extends Model
{
    protected $table = 'formation_user';

    protected $fillable = [
        'formation_id',
        'user_id',
        'team_id',
        'visible',
        'approved_at',
        'approved_by',
        'starts_at',
        'ends_at',
        'status',
        'current_lesson_id',
        'enrolled_at',
        'last_seen_at',
        'completed_at',
        'score_total',
        'max_score_total',
    ];

    protected function casts(): array
    {
        return [
            'formation_id' => 'integer',
            'user_id' => 'integer',
            'team_id' => 'integer',
            'visible' => 'boolean',
            'approved_at' => 'datetime',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'status' => 'string',
            'current_lesson_id' => 'integer',
            'enrolled_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'completed_at' => 'datetime',
            'score_total' => 'integer',
            'max_score_total' => 'integer',
        ];
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
