<?php

namespace App\Http\Controllers\Application\Eleve;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnableFormationRequest;
use App\Models\Formation;
use App\Models\Team;
use App\Services\FormationEnrollmentService;
use App\Services\FormationVisibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EleveController extends Controller
{
    private FormationVisibilityService $visibilityService;
    private FormationEnrollmentService $enrollmentService;

    public function __construct(
        FormationVisibilityService $visibilityService,
        FormationEnrollmentService $enrollmentService
    ) {
        $this->visibilityService = $visibilityService;
        $this->enrollmentService = $enrollmentService;
    }

    public function index(Team $team)
    {
        return view('application.eleve.index', compact('team'));
    }

    public function formationIndex(Team $team)
    {
        return view('application.eleve.formationsList', compact('team'));
    }

    public function formationPreview(Team $team, Formation $formation)
    {
        return view('application.eleve.formationPreview', compact('team', 'formation'));
    }

    public function formationContinue(Team $team, Formation $formation)
    {
        return view('application.eleve.formationContinue', compact('team', 'formation'));
    }

    public function formationShow(Team $team, Formation $formation)
    {
        // Vérifier que l'utilisateur peut accéder à cette formation
        if (!$this->visibilityService->isFormationVisibleForTeam($formation, $team)) {
            abort(403, 'Formation non accessible.');
        }

        return view('application.eleve.formationShow', compact('team', 'formation'));
    }

    public function formationEnable(EnableFormationRequest $request, Team $team, Formation $formation)
    {
        // La validation et l'autorisation se font dans EnableFormationRequest

        // Vérifier si déjà inscrit
        if ($this->enrollmentService->isUserEnrolled($formation)) {
            return redirect()->route('application.eleve.formations.continue', [$team, $formation])
                ->with('info', 'Vous êtes déjà inscrit à cette formation.');
        }

        // Inscrire l'utilisateur à la formation
        $this->enrollmentService->enrollUser($formation, $team);

        return redirect()->route('application.eleve.formations.continue', [$team, $formation])
            ->with('success', "La formation '{$formation->title}' a été activée.");
    }
}
