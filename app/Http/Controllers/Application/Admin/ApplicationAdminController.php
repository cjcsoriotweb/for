<?php

namespace App\Http\Controllers\Application\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\FormationService;
use App\Services\TeamService;

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
        $usersInTeam = (new TeamService())->getUsersInTeam($team);

        return view('application.admin.index', compact('team', 'formationsByTeam', 'formationsAll', 'usersInTeam'));
    }


}