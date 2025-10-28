<?php

namespace App\Http\Controllers\Clean\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Services\Clean\Account\AccountService;
use App\Services\FormationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {}

    public function overview(Request $request)
    {
        $user = Auth::user();
        $organisations = $this->accountService->teams()->listByUser($user);
        $defaultTeam = $organisations->first();

        $teamSearch = trim((string) $request->input('team_search', ''));
        $userSearch = trim((string) $request->input('user_search', ''));
        $formationSearch = trim((string) $request->input('formation_search', ''));

        $teamsQuery = Team::query()
            ->with(['owner:id,name,email'])
            ->withCount(['users', 'teamInvitations'])
            ->when($teamSearch !== '', function ($query) use ($teamSearch) {
                $query->where(function ($subQuery) use ($teamSearch) {
                    $subQuery
                        ->where('name', 'like', "%{$teamSearch}%")
                        ->orWhereHas('owner', function ($ownerQuery) use ($teamSearch) {
                            $ownerQuery
                                ->where('name', 'like', "%{$teamSearch}%")
                                ->orWhere('email', 'like', "%{$teamSearch}%");
                        });
                });
            })
            ->latest('updated_at');

        $teams = $teamsQuery->take(10)->get();

        $recentUsersQuery = User::query()
            ->select(['id', 'name', 'email', 'created_at', 'current_team_id'])
            ->with(['currentTeam:id,name'])
            ->withCount('teams')
            ->when($userSearch !== '', function ($query) use ($userSearch) {
                $query->where(function ($subQuery) use ($userSearch) {
                    $subQuery
                        ->where('name', 'like', "%{$userSearch}%")
                        ->orWhere('email', 'like', "%{$userSearch}%");
                });
            })
            ->latest('created_at')
            ->take(12);

        $recentUsers = $recentUsersQuery->get();

        $formationsQuery = Formation::query()
            ->withCount('teams')
            ->when($formationSearch !== '', function ($query) use ($formationSearch) {
                $query->where(function ($subQuery) use ($formationSearch) {
                    $subQuery
                        ->where('title', 'like', "%{$formationSearch}%")
                        ->orWhere('description', 'like', "%{$formationSearch}%");
                });
            })
            ->latest('updated_at')
            ->latest('created_at')
            ->take(12);

        $formations = $formationsQuery->get();

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
            'formations',
            'teamSearch',
            'userSearch',
            'formationSearch',
            'defaultTeam',
        ]));
    }

    public function teamsIndex(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $teams = Team::query()
            ->with(['owner:id,name,email'])
            ->withCount(['users', 'teamInvitations'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhereHas('owner', function ($ownerQuery) use ($search) {
                            $ownerQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('clean.admin.SuperadminTeamsPage', [
            'teams' => $teams,
            'search' => $search,
        ]);
    }

    public function usersIndex(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $users = User::query()
            ->select(['id', 'name', 'email', 'created_at', 'current_team_id'])
            ->with(['currentTeam:id,name'])
            ->withCount('teams')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('clean.admin.SuperadminUsersPage', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    public function formationsIndex(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $catalog = Formation::query()
            ->select(['id', 'title', 'description', 'updated_at', 'created_at'])
            ->withCount('teams')
            ->with('teams:id,name')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest('updated_at')
            ->paginate(18)
            ->withQueryString();

        return view('clean.admin.SuperadminFormationsPage', [
            'formations' => $catalog,
            'search' => $search,
        ]);
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
