<?php

namespace App\View\Components\Eleve;

use App\Models\Team;
use App\Services\Formation\StudentFormationService;
use App\Services\FormationEnrollmentService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class FormationChoice extends Component
{
    public Collection $availableFormations;

    public Collection $formations;

    public Team $team;

    /**
     * Create a new component instance.
     */
    public function __construct(
        StudentFormationService $studentFormationService,
        FormationEnrollmentService $formationEnrollmentService,
        Team $team
    ) {
        $this->team = $team;

        $user = Auth::user();

        $availableFormations = $studentFormationService
            ->listAvailableFormationsForTeamExceptCurrentUseByMe($team);

        $this->availableFormations = $availableFormations;

        $this->formations = $availableFormations->map(function ($formation) use (
            $studentFormationService,
            $formationEnrollmentService,
            $team,
            $user
        ) {
            $isEnrolled = $user
                ? $studentFormationService->isEnrolledInFormation($user, $formation, $team)
                : false;

            $progress = $isEnrolled && $user
                ? $studentFormationService->getStudentProgress($user, $formation)
                : null;

            $progressPercent = (int) ($progress['progress_percent'] ?? 0);
            $canAfford = $formationEnrollmentService->canTeamAffordFormation($team, $formation);

            $canJoin = ! $isEnrolled && $canAfford;

            return [
                'id' => $formation->id,
                'title' => $formation->title ?: 'Titre par defaut',
                'description' => $formation->description ?: 'Description par defaut',
                'cover_image_url' => $formation->cover_image_url ?: asset('images/formation-placeholder.svg'),
                'price_label' => $formation->money_amount
                    ? number_format((int) $formation->money_amount, 0, ',', ' ')
                    : 'Gratuit',
                'status_label' => $isEnrolled ? 'Deja inscrit' : 'Nouvelle formation',
                'is_enrolled' => $isEnrolled,
                'has_progress' => $isEnrolled && $progress !== null,
                'progress_percent' => $progressPercent,
                'show_route' => route('eleve.formation.show', [$team, $formation->id]),
                'enroll_route' => route('eleve.formation.enroll', [
                    'team' => $team,
                    'formation' => $formation->id,
                ]),
                'can_afford' => $canAfford,
                'can_join' => $canJoin,
                'enroll_button_label' => $canJoin ? 'Rejoindre cette formation' : null,
            ];
        });
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.eleve.formation.choice', [
            'team' => $this->team,
            'formations' => $this->formations,
        ]);
    }
}
