<?php

namespace App\View\Components\Eleve;

use App\Models\Team;
use App\Services\Formation\StudentFormationService;
use Closure;
use Illuminate\Contracts\View\View;
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

        if ($formations) {
            // Use formations passed from controller with progress data
            $this->formationsWithProgress = $formations->map(function ($formation) use ($studentFormationService) {
                // Ensure chapters and lessons are loaded for progress calculation
                $formation->load(['chapters.lessons']);

                // Recalculate progress to ensure it's accurate
                $totalLessons = $formation->chapters->pluck('lessons')->flatten()->count();
                $completedLessons = 0;

                foreach ($formation->chapters as $chapter) {
                    foreach ($chapter->lessons as $lesson) {
                        $lessonProgress = $lesson->learners()->where('user_id', Auth::user()->id)->first();
                        if ($lessonProgress && $lessonProgress->pivot->status === 'completed') {
                            $completedLessons++;
                        }
                    }
                }

                $calculatedProgressPercent = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

                // Update the progress data with calculated value
                if ($formation->progress_data) {
                    $formation->progress_data['progress_percent'] = $calculatedProgressPercent;
                }

                return $formation;
            });
        } else {
            // Fallback to service call if no formations provided
            $this->formationsWithProgress = $studentFormationService->listFormationCurrentByStudent($team, Auth::user());
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.eleve.FormationContinue', [
            'team' => $this->team,
            'formationsWithProgress' => $this->formationsWithProgress,
        ]);
    }
}
