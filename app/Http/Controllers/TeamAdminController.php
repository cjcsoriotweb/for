<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\User;


class TeamAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Team $team)
    {
        // Authorization check
        if (Gate::denies('access-admin', $team)) {
            abort(403);
        }

        // Fetch users associated with the team
        $users = $team->users;

        return view('team.admin.index', [
            'team' => $team,
            'users' => $users,
        ]);

  
    }

    public function formationsIndex(Team $team)
    {
        // Authorization check
        if (Gate::denies('access-admin', $team)) {
            abort(403);
        }


        return view('team.admin.formations.index', [
            'team' => $team,
        ]);
        

    }

    public function usersIndex(Team $team)
    {
        // Authorization check
        if (Gate::denies('access-admin', $team)) {
            abort(403);
        }

        // Fetch users associated with the team
        $users = $team->users;


    }

}