<?php

namespace App\Http\Controllers\Clean\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiTrainer;
use App\Models\Formation;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Models\SupportTicket;
use App\Services\Clean\Account\AccountService;
use App\Services\FormationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {}

    public function overview()
    {
        $stats = [
            'teams' => Team::count(),
            'users' => User::count(),
            'formations' => Formation::count(),
            'invitations' => TeamInvitation::count(),
            'tickets' => SupportTicket::count(),
            'ai_trainers' => AiTrainer::count(),
        ];

        return view('clean.admin.AdminOverviewPage', compact('stats'));
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

    public function supportIndex()
    {
        return view('clean.admin.SuperadminSupportPage');
    }

    public function aiIndex()
    {
        return view('clean.admin.SuperadminAiPage');
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
