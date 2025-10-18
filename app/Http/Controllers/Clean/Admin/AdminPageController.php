<?php

namespace App\Http\Controllers\Clean\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use App\Services\FormationService;
use App\Services\FormationVisibilityService;
use Illuminate\Support\Facades\Auth;

class AdminPageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {
    }

    public function home(Team $team, FormationService $formations)
    {


        $organisations = $this->accountService->teams()->listByUser(Auth::user());

        return view('clean.admin.AdminHomePage', compact(['organisations', 'team']));
    }

    public function users(Team $team)
    {
        $organisations = $this->accountService->teams()->listByUser(Auth::user());

        return view('clean.admin.AdminUsersPage', compact(['organisations', 'team']));
    }

    public function formations(Team $team)
    {
        $organisations = $this->accountService->teams()->listByUser(Auth::user());

        return view('clean.admin.AdminFormationsPage', compact(['organisations', 'team']));
    }


}