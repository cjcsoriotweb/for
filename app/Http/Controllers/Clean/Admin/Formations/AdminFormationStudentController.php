<?php

namespace App\Http\Controllers\Clean\Admin\Formations;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\FormationUser;
use App\Models\Lesson;
use App\Models\Team;
use App\Models\User;
use App\Services\Clean\Admin\Formations\AdminFormationStudentService;
use App\Services\Clean\Organisateur\OrganisateurService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminFormationStudentController extends Controller
{
    public function __construct(
        private readonly AdminFormationStudentService $studentService,
        private readonly OrganisateurService $organisateurService,
    ) {}

    public function show(Team $team, Formation $formation, User $student): View
    {
        $enrollment = FormationUser::query()
            ->where('formation_id', $formation->id)
            ->where('team_id', $team->id)
            ->where('user_id', $student->id)
            ->firstOrFail();

        $reportData = $this->organisateurService->getStudentReportData(
            $formation,
            $student,
            includeActivity: false
        );

        $chapters = $reportData['lessons']
            ->groupBy(fn ($lesson) => $lesson->chapter?->id ?? 'default')
            ->map(function ($lessons) {
                $chapter = $lessons->first()->chapter ?? null;

                return [
                    'chapter' => $chapter,
                    'lessons' => $lessons->values(),
                    'position' => $chapter?->position ?? 9999,
                ];
            })
            ->sortBy('position')
            ->values();

        $totalLessons = (int) ($reportData['totalLessons'] ?? 0);
        $completedLessons = (int) ($reportData['completedLessons'] ?? 0);
        $progressPercent = $totalLessons > 0
            ? (int) round(($completedLessons / $totalLessons) * 100)
            : 0;

        return view('in-application.admin.formations.student-progress', [
            'team' => $team,
            'formation' => $formation,
            'student' => $student,
            'enrollment' => $enrollment,
            'chapters' => $chapters,
            'totalLessons' => $totalLessons,
            'completedLessons' => $completedLessons,
            'inProgressLessons' => (int) ($reportData['inProgressLessons'] ?? 0),
            'notStartedLessons' => (int) ($reportData['notStartedLessons'] ?? 0),
            'progressPercent' => $progressPercent,
            'studentData' => $reportData['studentData'],
        ]);
    }

    public function completeLesson(
        Request $request,
        Team $team,
        Formation $formation,
        User $student,
        Lesson $lesson
    ): RedirectResponse {
        $this->studentService->completeLesson($formation, $team, $student, $lesson);

        return redirect()
            ->route('application.admin.formations.students.show', [$team, $formation, $student])
            ->with('status', __('Étape marquée comme complétée.'));
    }

    public function reset(Team $team, Formation $formation, User $student): RedirectResponse
    {
        $this->studentService->resetProgress($formation, $team, $student);

        return redirect()
            ->route('application.admin.formations.students.show', [$team, $formation, $student])
            ->with('status', __('La progression a été réinitialisée.'));
    }

    public function unenroll(Team $team, Formation $formation, User $student): RedirectResponse
    {
        $refunded = $this->studentService->unenrollStudent($formation, $team, $student, true);

        $message = __('L\'élève a été désinscrit de la formation.');
        if ($refunded > 0) {
            $message .= ' '.trans_choice(':count jeton a été recrédité.|:count jetons ont été recrédités.', $refunded, [
                'count' => $refunded,
            ]);
        }

        return redirect()
            ->route('application.admin.formations.revenue', [$team, $formation])
            ->with('status', $message);
    }
}
