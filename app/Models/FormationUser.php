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
        'completion_request_at',
        'completion_request_status',
        'trainer_signature_id',
        'completion_validated_at',
        'completion_validated_by',
        'completion_documents',
        'feedback_rating',
        'feedback_comment',
        'feedback_at',
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
            'completion_request_at' => 'datetime',
            'completion_validated_at' => 'datetime',
            'completion_documents' => 'array',
            'feedback_rating' => 'integer',
            'feedback_at' => 'datetime',
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



    public function completionValidatedBy()
    {
        return $this->belongsTo(User::class, 'completion_validated_by');
    }
}
