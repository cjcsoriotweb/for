<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TeamService;

class AccountRouting extends Controller
{
    public function acceptInvitation(Request $request, $id)
    {
        (new TeamService())->acceptInvitation($id);
        return redirect()->route('vous.index');
    }
    public function index()
    {
        return view('auth.vous.index', [
            'items' => (new TeamService())->getUsersTeam(),
            'invitations_pending' => (new TeamService())->getTeamInvitedMe(),
            'route' => 'team.show'
        ]);
    }
}
