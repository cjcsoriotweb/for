<?php

namespace App\Http\Controllers\Application\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\FormationService;

class ApplicationAdminController extends Controller
{


    public function index(Team $team, FormationService $formations)
    {
        $formationsByTeam = $team->formationsByTeam()->get();
        $formationsAll = $formations->paginateWithTeamFlags(
                team: $team,
                perPage: request('per_page', 15),
                search: request('q'),
                orderBy: request('order_by', 'title'),
                direction: request('direction', 'asc'),
            );

        return view('application.admin.index', ['team' => $team, 'formationsByTeam' => $formationsByTeam, 'formationsAll' => $formationsAll]);
    }


}