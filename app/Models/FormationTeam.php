<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FormationTeam extends Model
{
    //
    protected $table = 'formation_teams';
    protected $fillable = [
        'formation_id',
        'team_id',
        'visible',
        'approved_at',
        'approved_by',
        'starts_at',
        'ends_at',
    ];
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
    public function formation_user()
    {
        return $this->hasOne(FormationUser::class, 'formation_id', 'formation_id');
    }



}
