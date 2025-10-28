<?php

namespace App\Http\Controllers\Clean\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Services\Clean\Account\AccountService;
use App\Services\FormationService;
use Illuminate\Support\Facades\Auth;

class AdminPageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {}

    public function overview()
    {
        $user = Auth::user();
        $organisations = $this->accountService->teams()->listByUser($user);

        $teams = Team::query()
            ->with(['owner:id,name,email'])
            ->withCount(['users', 'teamInvitations'])
            ->latest('updated_at')
            ->take(10)
            ->get();

        $recentUsers = User::query()
            ->select(['id', 'name', 'email', 'created_at', 'current_team_id'])
            ->with(['currentTeam:id,name'])
            ->withCount('teams')
            ->latest('created_at')
            ->take(12)
            ->get();

        $stats = [
            'teams' => Team::count(),
            'users' => User::count(),
            'invitations' => TeamInvitation::count(),
        ];

        return view('clean.admin.AdminOverviewPage', compact([
            'organisations',
            'teams',
            'recentUsers',
            'stats',
        ]));
    }

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

        return view('clean.admin.AdminHomePage', compact([
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

        return view('clean.admin.AdminUsersPage', compact(['organisations', 'team']));
    }

    public function formations(Team $team)
    {
        $organisations = $this->accountService->teams()->listByUser(Auth::user());

        return view('clean.admin.AdminFormationsPage', compact(['organisations', 'team']));
    }

    public function configuration(Team $team)
    {
        $organisations = $this->accountService->teams()->listByUser(Auth::user());

        return view('clean.admin.AdminConfigurationPage', compact(['organisations', 'team']));
    }

    public function formationCreate(Team $team)
    {
        return view('clean.admin.formation.create', compact(['team']));
    }

    public function formationEdit(Team $team, $formation_id)
    {
        $formation = Formation::findOrFail($formation_id);

        return view('clean.admin.formation.editFormation', compact(['team', 'formation']));
    }
}
