<?php

namespace App\Http\Controllers\Clean\Organisateur;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use App\Services\Formation\StudentFormationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class OrganisateurPageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
        private readonly StudentFormationService $studentFormationService,
    ) {}

    public function home(Team $team)
    {
        $user = Auth::user();

        // Récupérer toutes les formations visibles pour cette équipe
        $formations = $this->studentFormationService->listAvailableFormationsForTeam($team);

        return view('clean.organisateur.home', compact(
            'team',
            'formations'
        ));
    }

    public function students(Team $team, \App\Models\Formation $formation)
    {
        // Vérifier que la formation est bien accessible à cette équipe
        $isFormationVisible = $formation->teams()
            ->where('teams.id', $team->id)
            ->where('formation_in_teams.visible', true)
            ->exists();

        if (! $isFormationVisible) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Formation non accessible.');
        }

        // Récupérer les étudiants inscrits à cette formation avec leurs données de pivot
        $students = $formation->learners()
            ->withPivot(['status', 'enrolled_at', 'last_seen_at', 'completed_at', 'score_total', 'max_score_total'])
            ->orderBy('formation_user.enrolled_at', 'desc')
            ->get();

        // Ajouter les données de leçons pour chaque étudiant
        foreach ($students as $student) {
            $student->lessons = $formation->lessons()
                ->with(['chapter' => function ($query) {
                    $query->orderBy('position');
                }])
                ->orderBy('position')
                ->get();

            // Ajouter les données de progression pour chaque leçon
            foreach ($student->lessons as $lesson) {
                $lessonProgress = $lesson->learners()
                    ->where('user_id', $student->id)
                    ->first();

                if ($lessonProgress) {
                    $lesson->pivot = $lessonProgress->pivot;
                } else {
                    $lesson->pivot = (object) [
                        'status' => 'enrolled',
                        'started_at' => null,
                        'last_activity_at' => null,
                        'completed_at' => null,
                        'watched_seconds' => 0,
                        'read_percent' => 0,
                        'attempts' => 0,
                        'best_score' => 0,
                        'max_score' => 0,
                    ];
                }
            }
        }

        return view('clean.organisateur.students', compact(
            'team',
            'formation',
            'students'
        ));
    }

    public function studentReport(Team $team, \App\Models\Formation $formation, \App\Models\User $student)
    {
        // Vérifier que la formation est bien accessible à cette équipe
        $isFormationVisible = $formation->teams()
            ->where('teams.id', $team->id)
            ->where('formation_in_teams.visible', true)
            ->exists();

        if (! $isFormationVisible) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Formation non accessible.');
        }

        // Vérifier que l'étudiant est bien inscrit à cette formation
        $isEnrolled = $formation->learners()
            ->where('user_id', $student->id)
            ->exists();

        if (! $isEnrolled) {
            return redirect()->route('organisateur.formations.students', [$team, $formation])
                ->with('error', 'Étudiant non inscrit à cette formation.');
        }

        // Récupérer les données détaillées de l'étudiant
        $studentData = $formation->learners()
            ->where('user_id', $student->id)
            ->withPivot(['status', 'enrolled_at', 'last_seen_at', 'completed_at', 'score_total', 'max_score_total'])
            ->first();

        // Récupérer toutes les leçons de la formation
        $lessons = $formation->lessons()
            ->with(['chapter' => function ($query) {
                $query->orderBy('position');
            }])
            ->orderBy('position')
            ->get();

        // Ajouter les données de progression pour l'étudiant spécifique
        foreach ($lessons as $lesson) {
            $lessonProgress = $lesson->learners()
                ->where('user_id', $student->id)
                ->first();

            if ($lessonProgress) {
                $lesson->pivot = $lessonProgress->pivot;
            } else {
                // Créer un objet pivot vide pour éviter les erreurs
                $lesson->pivot = (object) [
                    'status' => 'enrolled',
                    'started_at' => null,
                    'last_activity_at' => null,
                    'completed_at' => null,
                    'watched_seconds' => 0,
                    'read_percent' => 0,
                    'attempts' => 0,
                    'best_score' => 0,
                    'max_score' => 0,
                ];
            }
        }

        // Récupérer les tentatives de quiz
        $quizAttempts = \App\Models\QuizAttempt::where('user_id', $student->id)
            ->whereHas('lesson', function ($query) use ($formation) {
                $query->whereHas('chapter', function ($chapterQuery) use ($formation) {
                    $chapterQuery->where('formation_id', $formation->id);
                });
            })
            ->with([
                'lesson.lessonable.quizQuestions.quizChoices',
                'answers.question.quizChoices',
                'answers.choice',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculer les statistiques
        $totalLessons = $lessons->count();
        $completedLessons = $lessons->where('pivot.status', 'completed')->count();
        $inProgressLessons = $lessons->where('pivot.status', 'in_progress')->count();
        $notStartedLessons = $lessons->where('pivot.status', 'enrolled')->count();

        // Calculer le temps total passé
        $totalTimeSeconds = $lessons->sum('pivot.watched_seconds') ?? 0;
        $totalHours = floor($totalTimeSeconds / 3600);
        $totalMinutes = floor(($totalTimeSeconds % 3600) / 60);
        $totalSeconds = $totalTimeSeconds % 60;

        // Calculer le score moyen des quiz
        $averageQuizScore = 0;
        if ($quizAttempts->count() > 0) {
            $totalQuizScore = $quizAttempts->sum(function ($attempt) {
                return $attempt->score ?? 0;
            });
            $averageQuizScore = round($totalQuizScore / $quizAttempts->count(), 1);
        }

        return view('clean.organisateur.student-report', compact(
            'team',
            'formation',
            'student',
            'studentData',
            'lessons',
            'quizAttempts',
            'totalLessons',
            'completedLessons',
            'inProgressLessons',
            'notStartedLessons',
            'totalTimeSeconds',
            'totalHours',
            'totalMinutes',
            'totalSeconds',
            'averageQuizScore'
        ));
    }

    public function studentReportPdf(Team $team, \App\Models\Formation $formation, \App\Models\User $student)
    {
        // Vérifier que la formation est bien accessible à cette équipe
        $isFormationVisible = $formation->teams()
            ->where('teams.id', $team->id)
            ->where('formation_in_teams.visible', true)
            ->exists();

        if (! $isFormationVisible) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Formation non accessible.');
        }

        // Vérifier que l'étudiant est bien inscrit à cette formation
        $isEnrolled = $formation->learners()
            ->where('user_id', $student->id)
            ->exists();

        if (! $isEnrolled) {
            return redirect()->route('organisateur.formations.students', [$team, $formation])
                ->with('error', 'Étudiant non inscrit à cette formation.');
        }

        // Récupérer les données détaillées de l'étudiant
        $studentData = $formation->learners()
            ->where('user_id', $student->id)
            ->withPivot(['status', 'enrolled_at', 'last_seen_at', 'completed_at', 'score_total', 'max_score_total'])
            ->first();

        // Récupérer toutes les leçons de la formation
        $lessons = $formation->lessons()
            ->with(['chapter' => function ($query) {
                $query->orderBy('position');
            }])
            ->orderBy('position')
            ->get();

        // Ajouter les données de progression pour l'étudiant spécifique
        foreach ($lessons as $lesson) {
            $lessonProgress = $lesson->learners()
                ->where('user_id', $student->id)
                ->first();

            if ($lessonProgress) {
                $lesson->pivot = $lessonProgress->pivot;
            } else {
                // Créer un objet pivot vide pour éviter les erreurs
                $lesson->pivot = (object) [
                    'status' => 'enrolled',
                    'started_at' => null,
                    'last_activity_at' => null,
                    'completed_at' => null,
                    'watched_seconds' => 0,
                    'read_percent' => 0,
                    'attempts' => 0,
                    'best_score' => 0,
                    'max_score' => 0,
                ];
            }
        }

        // Récupérer les tentatives de quiz
        $quizAttempts = \App\Models\QuizAttempt::where('user_id', $student->id)
            ->whereHas('lesson', function ($query) use ($formation) {
                $query->whereHas('chapter', function ($chapterQuery) use ($formation) {
                    $chapterQuery->where('formation_id', $formation->id);
                });
            })
            ->with([
                'lesson.lessonable.quizQuestions.quizChoices',
                'answers.question.quizChoices',
                'answers.choice',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculer les statistiques
        $totalLessons = $lessons->count();
        $completedLessons = $lessons->where('pivot.status', 'completed')->count();
        $inProgressLessons = $lessons->where('pivot.status', 'in_progress')->count();
        $notStartedLessons = $lessons->where('pivot.status', 'enrolled')->count();

        // Calculer le temps total passé
        $totalTimeSeconds = $lessons->sum('pivot.watched_seconds') ?? 0;
        $totalHours = floor($totalTimeSeconds / 3600);
        $totalMinutes = floor(($totalTimeSeconds % 3600) / 60);
        $totalSeconds = $totalTimeSeconds % 60;

        // Calculer le score moyen des quiz
        $averageQuizScore = 0;
        if ($quizAttempts->count() > 0) {
            $totalQuizScore = $quizAttempts->sum(function ($attempt) {
                return $attempt->score ?? 0;
            });
            $averageQuizScore = round($totalQuizScore / $quizAttempts->count(), 1);
        }

        // Générer le PDF
        $pdf = Pdf::loadView('clean.organisateur.student-report-pdf', compact(
            'team',
            'formation',
            'student',
            'studentData',
            'lessons',
            'quizAttempts',
            'totalLessons',
            'completedLessons',
            'inProgressLessons',
            'notStartedLessons',
            'totalTimeSeconds',
            'totalHours',
            'totalMinutes',
            'totalSeconds',
            'averageQuizScore'
        ));

        // Retourner le PDF pour affichage dans iframe
        return $pdf->stream('rapport-'.$student->name.'.pdf');
    }

    public function studentReportPdfDownload(Team $team, \App\Models\Formation $formation, \App\Models\User $student)
    {
        // Vérifier que la formation est bien accessible à cette équipe
        $isFormationVisible = $formation->teams()
            ->where('teams.id', $team->id)
            ->where('formation_in_teams.visible', true)
            ->exists();

        if (! $isFormationVisible) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Formation non accessible.');
        }

        // Vérifier que l'étudiant est bien inscrit à cette formation
        $isEnrolled = $formation->learners()
            ->where('user_id', $student->id)
            ->exists();

        if (! $isEnrolled) {
            return redirect()->route('organisateur.formations.students', [$team, $formation])
                ->with('error', 'Étudiant non inscrit à cette formation.');
        }

        // Récupérer les données détaillées de l'étudiant
        $studentData = $formation->learners()
            ->where('user_id', $student->id)
            ->withPivot(['status', 'enrolled_at', 'last_seen_at', 'completed_at', 'score_total', 'max_score_total'])
            ->first();

        // Récupérer toutes les leçons de la formation
        $lessons = $formation->lessons()
            ->with(['chapter' => function ($query) {
                $query->orderBy('position');
            }])
            ->orderBy('position')
            ->get();

        // Ajouter les données de progression pour l'étudiant spécifique
        foreach ($lessons as $lesson) {
            $lessonProgress = $lesson->learners()
                ->where('user_id', $student->id)
                ->first();

            if ($lessonProgress) {
                $lesson->pivot = $lessonProgress->pivot;
            } else {
                // Créer un objet pivot vide pour éviter les erreurs
                $lesson->pivot = (object) [
                    'status' => 'enrolled',
                    'started_at' => null,
                    'last_activity_at' => null,
                    'completed_at' => null,
                    'watched_seconds' => 0,
                    'read_percent' => 0,
                    'attempts' => 0,
                    'best_score' => 0,
                    'max_score' => 0,
                ];
            }
        }

        // Récupérer les tentatives de quiz
        $quizAttempts = \App\Models\QuizAttempt::where('user_id', $student->id)
            ->whereHas('lesson', function ($query) use ($formation) {
                $query->whereHas('chapter', function ($chapterQuery) use ($formation) {
                    $chapterQuery->where('formation_id', $formation->id);
                });
            })
            ->with([
                'lesson.lessonable.quizQuestions.quizChoices',
                'answers.question.quizChoices',
                'answers.choice',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculer les statistiques
        $totalLessons = $lessons->count();
        $completedLessons = $lessons->where('pivot.status', 'completed')->count();
        $inProgressLessons = $lessons->where('pivot.status', 'in_progress')->count();
        $notStartedLessons = $lessons->where('pivot.status', 'enrolled')->count();

        // Calculer le temps total passé
        $totalTimeSeconds = $lessons->sum('pivot.watched_seconds') ?? 0;
        $totalHours = floor($totalTimeSeconds / 3600);
        $totalMinutes = floor(($totalTimeSeconds % 3600) / 60);
        $totalSeconds = $totalTimeSeconds % 60;

        // Calculer le score moyen des quiz
        $averageQuizScore = 0;
        if ($quizAttempts->count() > 0) {
            $totalQuizScore = $quizAttempts->sum(function ($attempt) {
                return $attempt->score ?? 0;
            });
            $averageQuizScore = round($totalQuizScore / $quizAttempts->count(), 1);
        }

        // Générer le PDF
        $pdf = Pdf::loadView('clean.organisateur.student-report-pdf', compact(
            'team',
            'formation',
            'student',
            'studentData',
            'lessons',
            'quizAttempts',
            'totalLessons',
            'completedLessons',
            'inProgressLessons',
            'notStartedLessons',
            'totalTimeSeconds',
            'totalHours',
            'totalMinutes',
            'totalSeconds',
            'averageQuizScore'
        ));

        // Télécharger le PDF
        return $pdf->download('rapport-'.$student->name.'-'.now()->format('Y-m-d').'.pdf');
    }
}
