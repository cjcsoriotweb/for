<?php

namespace App\View\Components\Eleve;

use App\Models\Team;
use App\Services\Formation\StudentFormationService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class FormationContinue extends Component
{
    public $currentFormation;

    public $team;

    public $formationsWithProgress;

    /**
     * Create a new component instance.
     */
    public function __construct(
        StudentFormationService $studentFormationService,
        Team $team,
        $formations = null
    ) {
        $this->team = $team;
        $user = Auth::user();

        $collection = $formations instanceof Collection
            ? $formations
            : ($formations ? collect($formations) : null);

        if (! $collection) {
            $collection = $studentFormationService->listFormationCurrentByStudent(
                $team,
                $user
            );
        }

        $this->formationsWithProgress = $collection->map(function ($formation) use ($studentFormationService, $user) {
            if (! $formation->progress_data && $user) {
                $formation->progress_data = $studentFormationService->getStudentProgress($user, $formation)
                    ?? $this->defaultProgressData();
            } elseif (! $formation->progress_data) {
                $formation->progress_data = $this->defaultProgressData();
            }

            if (! array_key_exists('progress_percent', $formation->progress_data ?? [])) {
                $formation->progress_data['progress_percent'] = 0;
            }

            $formation->is_completed = (bool) ($formation->is_completed ?? false);

            return $formation;
        });
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.eleve.formation.continue', [
            'team' => $this->team,
            'formationsWithProgress' => $this->formationsWithProgress,
        ]);
    }

    private function defaultProgressData(): array
    {
        return [
            'status' => 'enrolled',
            'progress_percent' => 0,
            'current_lesson_id' => null,
            'enrolled_at' => now(),
            'last_seen_at' => now(),
            'completed_at' => null,
            'score_total' => 0,
            'max_score_total' => 0,
        ];
    }
}
