<?php

namespace App\Http\Controllers\Clean\Admin\Formations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Formations\FormationCreateNameByTeam;
use App\Http\Requests\Admin\Formations\FormationUpdateVisibilityByTeam;
use App\Models\Formation;
use App\Models\FormationInTeams;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use App\Services\FormationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

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

        return redirect()->back()->with('status', __('Formation crÃ©Ã©e avec succÃ¨s!'));
    }

    public function updateVisibilityByTeam(FormationUpdateVisibilityByTeam $request, Team $team, FormationService $formationService)
    {
        $validated = $request->validated();
        $formation = Formation::findOrFail($validated['formation_id']);

        $enabled = (bool) $validated['enabled'];

        if ($enabled) {
            $usageQuota = (int) $validated['usage_quota'];

            $existingPivot = FormationInTeams::query()
                ->where('formation_id', $formation->id)
                ->where('team_id', $team->id)
                ->first();

            if ($existingPivot && $usageQuota < $existingPivot->usage_consumed) {
                return redirect()
                    ->route('application.admin.formations.index', $team)
                    ->withErrors([
                        'usage_quota' => __('Le quota doit être supérieur ou égal aux utilisations déjà consommées (:count).', [
                            'count' => $existingPivot->usage_consumed,
                        ]),
                    ]);
            }

            $formationService->admin()->makeFormationVisibleForTeam($formation, $team, $usageQuota);

            return redirect()
                ->route('application.admin.formations.index', $team)
                ->with('status', __('Formation activée ou mise à jour avec succès !'));
        }

        $formationService->admin()->makeFormationInvisibleForTeam($formation, $team);

        return redirect()
            ->route('application.admin.formations.index', $team)
            ->with('status', __('Formation désactivée avec succès !'));
    }

    public function revenueSummary(Request $request, Team $team, Formation $formation): RedirectResponse
    {
        return redirect()
            ->route('application.admin.formations.index', $team)
            ->with('status', __('La page de revenus n’est plus disponible.'));
    }
