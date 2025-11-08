<?php

namespace App\Http\Controllers\Clean\Organisateur;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\Quiz;
use App\Models\Team;
use App\Models\TextContent;
use App\Models\User;
use App\Models\VideoContent;
use App\Services\Clean\Organisateur\OrganisateurService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

class OrganisateurPageController extends Controller
{
    public function __construct(
        private readonly OrganisateurService $organisateurService,
    ) {}

    public function home(Team $team)
    {
        $formations = $this->organisateurService->listVisibleFormations($team);

        return view('in-application.organisateur.home', compact('team', 'formations'));
    }

    public function catalogue(Request $request, Team $team)
    {
        $visibleFormations = $this->organisateurService->listVisibleFormations($team);

        $allFormations = Formation::withCount(['learners', 'lessons'])
            ->with(['lessons' => function ($query) {
                $query->select('lessons.id', 'lessons.chapter_id', 'lessons.title', 'lessons.lessonable_type', 'lessons.lessonable_id')
                    ->with(['lessonable' => function (MorphTo $morphTo) {
                        $morphTo->morphWith([
                            VideoContent::class => [],
                            TextContent::class => [],
                            Quiz::class => ['quizQuestions:id,quiz_id'],
                        ]);
                    }]);
            }])
            ->orderBy('title')
            ->get();

        // Count different content types and calculate duration for each formation
        $allFormations->each(function ($formation) {
            $formation->video_count = $formation->lessons->where('lessonable_type', VideoContent::class)->count();
            $formation->quiz_count = $formation->lessons->where('lessonable_type', Quiz::class)->count();
            $formation->text_count = $formation->lessons->where('lessonable_type', TextContent::class)->count();

            // Calculate total duration in minutes
            $totalDuration = 0;

            foreach ($formation->lessons as $lesson) {
                switch ($lesson->lessonable_type) {
                    case VideoContent::class:
                        $totalDuration += $lesson->lessonable->duration_minutes ?? 0;
                        break;
                    case TextContent::class:
                        $totalDuration += $lesson->lessonable->estimated_read_time ?? 0;
                        break;
                    case Quiz::class:
                        if (! $lesson->lessonable) {
                            break;
                        }

                        $estimated = (int) ($lesson->lessonable->estimated_duration_minutes ?? 0);

                        if ($estimated > 0) {
                            $totalDuration += $estimated;
                            break;
                        }

                        // Fallback estimate: 2 minutes per question, minimum 5 for non-empty quizzes
                        $questionCount = $lesson->lessonable->quizQuestions?->count() ?? 0;
                        $totalDuration += $questionCount > 0 ? max($questionCount * 2, 5) : 0;
                        break;
                }
            }

            $formation->total_duration_minutes = $totalDuration;
        });

        return view('in-application.organisateur.catalogue', compact(
            'team',
            'visibleFormations',
            'allFormations'
        ));
    }

    public function show(Team $team, Formation $formation)
    {
        // Get detailed formation data with content counts
        $formationWithDetails = Formation::withCount(['learners', 'lessons'])
            ->with([
                'lessons' => function ($query) {
                    $query->select('lessons.id', 'lessons.chapter_id', 'lessons.title', 'lessons.lessonable_type', 'lessons.lessonable_id');
                },
                'chapters.lessons' => function ($query) {
                    $query->select('lessons.id', 'lessons.chapter_id', 'lessons.title', 'lessons.lessonable_type', 'lessons.lessonable_id');
                },
            ])
            ->find($formation->id);

        // Count different content types
        $videoCount = $formationWithDetails->lessons->where('lessonable_type', 'App\\Models\\VideoContent')->count();
        $quizCount = $formationWithDetails->lessons->where('lessonable_type', 'App\\Models\\Quiz')->count();
        $textCount = $formationWithDetails->lessons->where('lessonable_type', 'App\\Models\\TextContent')->count();

        // Check if formation is visible to this team (for display purposes only)
        $isVisible = $this->organisateurService->formationIsVisibleToTeam($team, $formation);

        return view('in-application.organisateur.formation-show', compact(
            'team',
            'formation',
            'formationWithDetails',
            'videoCount',
            'quizCount',
            'textCount',
            'isVisible'
        ));
    }

    public function users(Team $team)
    {
        return view('in-application.organisateur.users', compact('team'));
    }

    public function students(Request $request, Team $team, Formation $formation)
    {
        if (! $this->organisateurService->formationIsVisibleToTeam($team, $formation)) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Formation non accessible.');
        }

        $filters = [
            'search' => $request->query('search', ''),
            'status' => $request->query('status'),
        ];

        $overview = $this->organisateurService->getStudentsOverview($formation, $team, $filters);

        return view('in-application.organisateur.students', [
            'team' => $team,
            'formation' => $formation,
            'students' => $overview['students'],
            'studentSummaries' => $overview['studentSummaries'],
            'search' => trim((string) $filters['search']),
            'statusFilter' => $filters['status'],
            'stats' => $overview['stats'],
            'monthlyCost' => $overview['monthlyCost'],
            'monthlyEnrollmentsCount' => $overview['monthlyEnrollmentsCount'],
        ]);
    }

    public function studentsCost(Request $request, Team $team, Formation $formation)
    {
        if (! $this->organisateurService->formationIsVisibleToTeam($team, $formation)) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Formation non accessible.');
        }

        $summary = $this->organisateurService->getStudentsCostSummary(
            $formation,
            $team,
            $request->query('month')
        );

        return view('in-application.organisateur.students-cost', array_merge([
            'team' => $team,
            'formation' => $formation,
        ], $summary));
    }

    public function studentReport(Request $request, Team $team, Formation $formation, User $student)
    {
        if (! $this->organisateurService->formationIsVisibleToTeam($team, $formation)) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Formation non accessible.');
        }

        if (! $this->organisateurService->studentIsEnrolledInFormation($formation, $student)) {
            return redirect()->route('organisateur.formations.students', [$team, $formation])
                ->with('error', 'Eleve non inscrit a cette formation.');
        }

        $activityFilters = [
            'activity_search' => $request->get('activity_search'),
            'lesson_filter' => $request->get('lesson_filter'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        $reportData = $this->organisateurService->getStudentReportData(
            $formation,
            $student,
            $activityFilters,
            true
        );

        return view('in-application.organisateur.student-report', array_merge([
            'team' => $team,
            'formation' => $formation,
            'student' => $student,
        ], $reportData));
    }

    public function studentReportPdf(Team $team, Formation $formation, User $student)
    {
        if (! $this->organisateurService->formationIsVisibleToTeam($team, $formation)) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Formation non accessible.');
        }

        if (! $this->organisateurService->studentIsEnrolledInFormation($formation, $student)) {
            return redirect()->route('organisateur.formations.students', [$team, $formation])
                ->with('error', 'Eleve non inscrit a cette formation.');
        }

        $reportData = $this->organisateurService->getStudentReportData(
            $formation,
            $student,
            includeActivity: false
        );

        $pdf = Pdf::loadView('in-application.organisateur.student-report-pdf', array_merge([
            'team' => $team,
            'formation' => $formation,
            'student' => $student,
        ], $reportData));

        return $pdf->stream('rapport-'.$student->name.'.pdf');
    }

    public function studentReportPdfDownload(Team $team, Formation $formation, User $student)
    {
        if (! $this->organisateurService->formationIsVisibleToTeam($team, $formation)) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Formation non accessible.');
        }

        if (! $this->organisateurService->studentIsEnrolledInFormation($formation, $student)) {
            return redirect()->route('organisateur.formations.students', [$team, $formation])
                ->with('error', 'Eleve non inscrit a cette formation.');
        }

        $reportData = $this->organisateurService->getStudentReportData(
            $formation,
            $student,
            includeActivity: false
        );

        $pdf = Pdf::loadView('in-application.organisateur.student-report-pdf', array_merge([
            'team' => $team,
            'formation' => $formation,
            'student' => $student,
        ], $reportData));

        return $pdf->download('rapport-'.$student->name.'-'.now()->format('Y-m-d').'.pdf');
    }
}
