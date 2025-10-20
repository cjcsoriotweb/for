<?php

namespace App\Services\Formation;

use App\Models\Team;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class StudentFormationService extends BaseFormationService
{

    public function listFormationCurrentByStudent(Team $team, User $user)
    {
        return $this->list([] + ['team' => $team]);
    }
}
