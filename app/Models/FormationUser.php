<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormationUser extends Model
{
    protected $table = 'formation_user';
    protected $fillable = [
        'formation_id',
        'user_id',
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
}
