<?php

namespace App\Http\Controllers\Clean\Admin\Formations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Formations\FormationCreateNameByTeam;
use App\Http\Requests\Admin\Formations\FormationUpdateVisibilityByTeam;
use App\Models\Formation;
use App\Models\FormationUser;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use App\Services\FormationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class AdminFormationController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {}

    public function storeNewFormationByTitle(FormationCreateNameByTeam $request)
    {
        $validated = $request->validated();
        $title = $validated['formation']['title'];
        $description = $validated['formation']['description'];

        app(FormationService::class)->createFormation(['title' => $title, 'description' => $description]);

        return redirect()->back()->with('status', __('Formation créée avec succès!'));
    }

    public function updateVisibilityByTeam(FormationUpdateVisibilityByTeam $request, Team $team, FormationService $formationService)
    {
        $validated = $request->validated();
        $formation = Formation::findOrFail($validated['formation_id']);

        $enabled = $validated['enabled'];

        if ($enabled) {
            $formationService->admin()->makeFormationVisibleForTeam($formation, $team);

            return redirect()->route('application.admin.formations.index', $team)->with('status', __('Formation activée avec succès!'));
        }

        $formationService->admin()->makeFormationInvisibleForTeam($formation, $team);

        return redirect()->route('application.admin.formations.index', $team)->with('status', __('Formation désactivée avec succès!'));
    }

    public function revenueSummary(Request $request, Team $team, Formation $formation): View
    {
        $availableMonths = FormationUser::query()
            ->where('formation_id', $formation->id)
            ->get(['enrolled_at', 'created_at'])
            ->map(function ($pivot) {
                $date = $pivot->enrolled_at ?? $pivot->created_at;

                return $date ? Carbon::make($date)?->format('Y-m') : null;
            })
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        $selectedMonth = $request->query('month', $availableMonths->first() ?? Carbon::now()->format('Y-m'));

        try {
            $periodStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        } catch (\Throwable $exception) {
            $periodStart = Carbon::now()->startOfMonth();
            $selectedMonth = $periodStart->format('Y-m');
        }

        $periodEnd = $periodStart->copy()->endOfMonth();

        $teamIds = FormationUser::query()
            ->where('formation_id', $formation->id)
            ->pluck('team_id')
            ->filter()
            ->unique()
            ->values();

        $teams = Team::query()
            ->whereIn('id', $teamIds)
            ->orderBy('name')
            ->get();

        $selectedTeamId = $request->query('team');

        $enrollmentsQuery = FormationUser::query()
            ->with(['user', 'team'])
            ->where('formation_id', $formation->id)
            ->where(function ($query) use ($periodStart, $periodEnd) {
                $query->whereBetween('enrolled_at', [$periodStart, $periodEnd])
                    ->orWhere(function ($subQuery) use ($periodStart, $periodEnd) {
                        $subQuery->whereNull('enrolled_at')
                            ->whereBetween('created_at', [$periodStart, $periodEnd]);
                    });
            })
            ->orderByDesc('enrolled_at')
            ->orderByDesc('created_at');

        if (! empty($selectedTeamId) && $selectedTeamId !== 'all') {
            $enrollmentsQuery->where('team_id', (int) $selectedTeamId);
        }

        $enrollments = $enrollmentsQuery->get();

        $totalRevenue = $enrollments->sum(function ($row) use ($formation) {
            return (int) ($row->enrollment_cost ?? $formation->money_amount ?? 0);
        });

        $teamSummaries = $this->groupEnrollmentsByTeam($enrollments, $formation);

        if ($availableMonths->doesntContain($selectedMonth)) {
            $availableMonths = $availableMonths->push($selectedMonth)->sortDesc()->values();
        }

        return view('in-application.admin.formations.revenue-details', [
            'team' => $team,
            'formation' => $formation,
            'enrollments' => $enrollments,
            'selectedMonth' => $selectedMonth,
            'availableMonths' => $availableMonths,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'totalRevenue' => $totalRevenue,
            'teams' => $teams,
            'selectedTeamId' => $selectedTeamId,
            'teamSummaries' => $teamSummaries,
        ]);
    }

    protected function groupEnrollmentsByTeam(Collection $enrollments, Formation $formation): Collection
    {
        return $enrollments
            ->groupBy('team_id')
            ->map(function ($rows) use ($formation) {
                $team = $rows->first()?->team;
                $totalTokens = $rows->sum(function ($row) use ($formation) {
                    return (int) ($row->enrollment_cost ?? $formation->money_amount ?? 0);
                });

                return [
                    'team' => $team,
                    'total_tokens' => $totalTokens,
                    'enrollments' => $rows->count(),
                ];
            })
            ->sortByDesc('total_tokens')
            ->values();
    }
}
