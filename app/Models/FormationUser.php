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

    protected function casts(): array
    {
        return [
            'formation_id' => 'integer',
            'user_id' => 'integer',
            'visible' => 'boolean',
            'approved_at' => 'datetime',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
}
