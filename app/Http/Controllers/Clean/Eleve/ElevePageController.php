<?php

namespace App\Http\Controllers\Clean\Eleve;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use App\Services\Formation\StudentFormationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ElevePageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
        private readonly StudentFormationService $studentFormationService,
    ) {}

    public function home(Team $team)
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Exemple 1: Lister les formations actuelles de l'étudiant
        $formations = $this->studentFormationService->listFormationCurrentByStudent($team, $user);

        // Exemple 2: Paginer les formations (15 par page)
        $formationsPaginees = $this->studentFormationService->paginateFormationCurrentByStudent($team, $user, 10);

        // Exemple 3: Vérifier si l'étudiant est inscrit à une formation spécifique
        $formation = Formation::find(1); // Récupérer une formation spécifique
        $isEnrolled = $this->studentFormationService->isEnrolledInFormation($user, $formation);

        // Exemple 4: Récupérer le progrès d'un étudiant dans une formation
        $progress = $this->studentFormationService->getStudentProgress($user, $formation);

        // Exemple 5: Récupérer une formation avec les données de progrès
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        return view('clean.eleve.home', compact(
            'team',
            'formations',
            'formationsPaginees',
            'isEnrolled',
            'progress',
            'formationWithProgress'
        ));
    }

    /**
     * Afficher les détails d'une formation pour un étudiant
     */
    public function showFormation(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // Vérifier si l'étudiant est inscrit
        if (!$this->studentFormationService->isEnrolledInFormation($user, $formation)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Récupérer la formation avec le progrès de l'étudiant
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        if (!$formationWithProgress) {
            abort(404, 'Formation non trouvée ou non accessible.');
        }

        // Récupérer le progrès détaillé
        $progress = $this->studentFormationService->getStudentProgress($user, $formation);

        return view('clean.eleve.formation.show', compact(
            'team',
            'formationWithProgress',
            'progress'
        ));
    }

    /**
     * API endpoint pour récupérer les formations d'un étudiant (pour AJAX)
     */
    public function apiFormations(Team $team, Request $request)
    {
        $user = Auth::user();
        $perPage = $request->get('per_page', 15);

        $formations = $this->studentFormationService->paginateFormationCurrentByStudent(
            $team,
            $user,
            $perPage
        );

        return response()->json($formations);
    }
}
