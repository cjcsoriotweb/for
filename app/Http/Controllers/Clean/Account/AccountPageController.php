<?php

namespace App\Http\Controllers\Clean\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\account\team\SwitchTeamRequest;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;

class AccountPageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {
    }

    public function dashboard()
    {
        $organisations = $this->accountService->teams()->listByUser(Auth::user());

        return view('clean.account.dashboard', compact('organisations'));
    }

    public function switch(SwitchTeamRequest $request)
    {
        $request = $request->validated();

        $team = Team::find($request['team_id']);
        if (!$team) {
            abort(404);
        }

        return $this->accountService->teams()->switchTeam(Auth::user(), $team);
    }
}