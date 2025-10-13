<?php

namespace App\Http\Controllers\Team\admin;
use App\Models\Team;
use App\Http\Controllers\Controller;


class TeamAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['auth','verified']);
        $this->middleware('can:access-admin,team');
    }


    public function index(Team $team)
    {
        $users = $team->users;

        return view('team.admin.index', [
            'team' => $team,
            'users' => $users,
        ]);

  
    }
    
    public function usersIndex(Team $team)
    {
        return view('team.admin.users.index', [
            'team' => $team,
        ]);
    }


    public function formationsIndex(Team $team)
    {
        return view('team.admin.formations.index', [
            'team' => $team,
        ]);
        

    }




}