<?php

namespace App\Livewire\Formateur\Formations;

use App\Models\Formation;
use App\Models\FormationUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class FormationsList extends Component
{
    protected function formationsQuery()
    {
        $query = Formation::withCount(['learners', 'lessons'])
            ->with([
                'lessons' => function ($query) {
                    $query->select('lessons.id', 'lessons.chapter_id', 'lessons.title', 'lessons.lessonable_type', 'lessons.lessonable_id');
                },
                'lessons.lessonable'
            ]);

        // Appliquer le filtre utilisateur seulement si ce n'est pas un superadmin
        if (! Auth::user()->superadmin) {
            $query->where('user_id', '=', Auth::user()->id);
        }

        return $query;
    }

    protected function shapeFormationData($formation)
    {
        // Shape data for the view to keep Blade templates presentation-only.
        $formation->video_count = $formation->lessons->where('lessonable_type', 'App\\Models\\VideoContent')->count();
        $formation->quiz_count = $formation->lessons->where('lessonable_type', 'App\\Models\\Quiz')->count();
        $formation->text_count = $formation->lessons->where('lessonable_type', 'App\\Models\\TextContent')->count();

        $formation->card_lessons_count = $formation->lessons_count ?? 0;
        $formation->card_learners_count = $formation->learners_count ?? 0;
        $formation->card_description = Str::limit((string) $formation->description, 180);

        // Calculate average completion percentage for this formation
        // Since progress_percent column was removed, use a default value or calculate based on status
        $completedCount = FormationUser::where('formation_id', $formation->id)
            ->where('status', 'completed')
            ->count();
        $totalEnrolled = FormationUser::where('formation_id', $formation->id)->count();

        $avgCompletion = $totalEnrolled > 0 ? ($completedCount / $totalEnrolled) * 100 : 0;
        $formation->card_completion_percentage = min(100, round($avgCompletion, 1));

        $formation->card_created_label = $formation->created_at instanceof Carbon
            ? $formation->created_at->diffForHumans()
            : null;
        $formation->card_is_active = (bool) $formation->active;

        // Calculate total duration in minutes.
        $totalDuration = 0;

        foreach ($formation->lessons as $lesson) {
            switch ($lesson->lessonable_type) {
                case 'App\\Models\\VideoContent':
                    $totalDuration += $lesson->lessonable?->duration_minutes ?? 0;
                    break;
                case 'App\\Models\\TextContent':
                    $totalDuration += $lesson->lessonable?->estimated_read_time ?? 0;
                    break;
                case 'App\\Models\\Quiz':
                    if ($lesson->lessonable) {
                        $estimated = (int) ($lesson->lessonable->estimated_duration_minutes ?? 0);

                        if ($estimated > 0) {
                            $totalDuration += $estimated;
                            break;
                        }

                        $questionCount = $lesson->lessonable->quizQuestions()->count();
                        $totalDuration += $questionCount > 0 ? max($questionCount * 2, 5) : 0;
                    }
                    break;
            }
        }

        $formation->total_duration_minutes = $totalDuration;

        return $formation;
    }

    public function render()
    {
        $formations = $this->formationsQuery()
            ->get()
            ->map(function ($formation) {
                return $this->shapeFormationData($formation);
            });

        // Calculate dashboard statistics
        $stats = $this->calculateDashboardStats();

        return view('livewire.formateur.formations.formations-list', [
            'formations' => $formations,
            'stats' => $stats,
        ]);
    }

    protected function calculateDashboardStats()
    {
        $userId = Auth::user()->id;
        $isSuperadmin = Auth::user()->superadmin;

        // Base query for formations
        $formationsQuery = Formation::query();
        if (!$isSuperadmin) {
            $formationsQuery->where('user_id', $userId);
        }

        // Total formations
        $totalFormations = $formationsQuery->count();

        // Active formations
        $activeFormations = (clone $formationsQuery)->where('active', true)->count();

        // Total learners across all formations
        $totalLearners = FormationUser::whereHas('formation', function ($query) use ($userId, $isSuperadmin) {
            if (!$isSuperadmin) {
                $query->where('user_id', $userId);
            }
        })->distinct('user_id')->count();

        // Average completion rate - calculate based on completed status
        $totalCompleted = FormationUser::whereHas('formation', function ($query) use ($userId, $isSuperadmin) {
            if (!$isSuperadmin) {
                $query->where('user_id', $userId);
            }
        })->where('status', 'completed')->count();

        $totalEnrolledAll = FormationUser::whereHas('formation', function ($query) use ($userId, $isSuperadmin) {
            if (!$isSuperadmin) {
                $query->where('user_id', $userId);
            }
        })->count();

        $avgCompletionRate = $totalEnrolledAll > 0 ? ($totalCompleted / $totalEnrolledAll) * 100 : 0;

        // Recent formations (last 30 days)
        $recentFormations = (clone $formationsQuery)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        // Total lessons across all formations
        $totalLessons = DB::table('lessons')
            ->join('chapters', 'lessons.chapter_id', '=', 'chapters.id')
            ->join('formations', 'chapters.formation_id', '=', 'formations.id')
            ->when(!$isSuperadmin, function ($query) use ($userId) {
                $query->where('formations.user_id', $userId);
            })
            ->count();

        return [
            'total_formations' => $totalFormations,
            'active_formations' => $activeFormations,
            'total_learners' => $totalLearners,
            'avg_completion_rate' => round($avgCompletionRate, 1),
            'recent_formations' => $recentFormations,
            'total_lessons' => $totalLessons,
        ];
    }
}
