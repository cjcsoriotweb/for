<?php

namespace App\Http\Controllers\Clean\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\AiTrainer;
use App\Models\Formation;
use App\Models\SupportTicket;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SuperadminPageController extends Controller
{
    public function overview()
    {
        $stats = [
            'teams' => Team::count(),
            'users' => User::count(),
            'formations' => Formation::count(),
            'invitations' => TeamInvitation::count(),
            'tickets' => SupportTicket::count(),
        ];

        $trainerCount = AiTrainer::count();

        return view('out-application.superadmin.superadmin-overview-page', [
            'stats' => $stats,
            'trainerCount' => $trainerCount,
        ]);
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

        return view('out-application.superadmin.superadmin-teams-page', [
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

        return view('out-application.superadmin.superadmin-users-page', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    public function formationsIndex(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $sort = (string) $request->query('sort', 'revenue_desc');
        $sortKey = Str::beforeLast($sort, '_');
        $sortDirection = Str::endsWith($sort, '_asc') ? 'asc' : 'desc';

        $aggregatesSub = DB::table('formation_user')
            ->selectRaw('formation_user.formation_id, COUNT(*) as enrollments_count, SUM(COALESCE(formation_user.enrollment_cost, f.money_amount, 0)) as revenue_sum, MAX(COALESCE(formation_user.enrolled_at, formation_user.created_at)) as last_enrollment_at')
            ->join('formations as f', 'f.id', '=', 'formation_user.formation_id')
            ->groupBy('formation_user.formation_id');

        $baseQuery = Formation::query()
            ->select([
                'formations.*',
                DB::raw('COALESCE(stats.enrollments_count, 0) as enrollments_count'),
                DB::raw('COALESCE(stats.revenue_sum, 0) as revenue_sum'),
                DB::raw('stats.last_enrollment_at'),
            ])
            ->leftJoinSub($aggregatesSub, 'stats', 'stats.formation_id', '=', 'formations.id')
            ->withCount('teams')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('formations.title', 'like', "%{$search}%")
                        ->orWhere('formations.description', 'like', "%{$search}%");
                });
            });

        $catalog = (clone $baseQuery)
            ->with('teams:id,name')
            ->orderBy('formations.updated_at', 'desc')
            ->paginate(18)
            ->withQueryString();

        $orderColumn = match ($sortKey) {
            'enrollments' => 'enrollments_count',
            'title' => 'formations.title',
            'updated_at' => 'formations.updated_at',
            default => 'revenue_sum',
        };

        $revenueRows = (clone $baseQuery)
            ->orderBy($orderColumn, $sortDirection)
            ->orderBy('formations.title')
            ->limit(50)
            ->get();

        return view('out-application.superadmin.superadmin-formations-page', [
            'formations' => $catalog,
            'search' => $search,
            'revenueRows' => $revenueRows,
            'sort' => $sort,
        ]);
    }

    public function supportIndex()
    {
        return view('out-application.superadmin.superadmin-support-page');
    }
}
