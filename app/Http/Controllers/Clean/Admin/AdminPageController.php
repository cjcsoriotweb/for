<?php

namespace App\Http\Controllers\Clean\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use App\Services\FormationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {}

    public function home(Team $team, FormationService $formations)
    {
        $organisations = $this->accountService->teams()->listByUser(Auth::user());
        $adminService = $formations->admin();
        $catalog = $adminService->listWithTeamFlags($team);

        $recentFormations = $catalog
            ->sortByDesc(static fn ($formation) => $formation->updated_at ?? $formation->created_at)
            ->take(5);

        $managedUsers = $team->allUsers()
            ->sortByDesc(static fn ($user) => optional($user->pivot)->created_at ?? $user->created_at)
            ->take(6);

        $pendingInvitations = $team->teamInvitations()
            ->latest()
            ->take(5)
            ->get();

        return view('in-application.admin.admin-home-page', compact([
            'organisations',
            'team',
            'recentFormations',
            'managedUsers',
            'pendingInvitations',
        ]));
    }

    public function users(Team $team)
    {
        $organisations = $this->accountService->teams()->listByUser(Auth::user());

        return view('in-application.admin.admin-users-page', compact(['organisations', 'team']));
    }

    public function formations(Team $team)
    {
        $organisations = $this->accountService->teams()->listByUser(Auth::user());

        return view('in-application.admin.admin-formations-page', compact(['organisations', 'team']));
    }

    public function configuration(Team $team)
    {
        $organisations = $this->accountService->teams()->listByUser(Auth::user());

        return view('in-application.admin.admin-configuration-page', compact(['organisations', 'team']));
    }

    public function formationCreate(Team $team)
    {
        return view('in-application.admin.formation.create', compact(['team']));
    }

    public function formationEdit(Team $team, $formation_id)
    {
        $formation = Formation::findOrFail($formation_id);

        return view('in-application.admin.formation.edit-formation', compact(['team', 'formation']));
    }
}
