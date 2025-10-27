<?php

namespace App\Http\Controllers\Clean\Organisateur;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\Team;
use App\Models\User;
use App\Services\Clean\Organisateur\OrganisateurService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrganisateurPageController extends Controller
{
    public function __construct(
        private readonly OrganisateurService $organisateurService,
    ) {}

    public function home(Team $team)
    {
        $formations = $this->organisateurService->listVisibleFormations($team);

        return view('clean.organisateur.home', compact('team', 'formations'));
    }

    public function catalogue(Request $request, Team $team)
    {
        $search = $request->query('search', '');
        $filter = $request->query('filter', 'all'); // all, visible, hidden
        $sortBy = $request->query('sort', 'title'); // title, price, created_at
        $sortDirection = $request->query('direction', 'asc'); // asc, desc

        $visibleFormations = $this->organisateurService->listVisibleFormations($team);

        // Build query for all formations with search and filters
        $formationsQuery = Formation::withCount(['learners', 'lessons'])
            ->with(['lessons' => function ($query) {
                $query->select('lessons.id', 'lessons.chapter_id', 'lessons.title', 'lessons.lessonable_type', 'lessons.lessonable_id');
            }]);

        // Apply search filter
        if (! empty($search)) {
            $formationsQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('level', 'like', "%{$search}%");
            });
        }

        // Apply visibility filter
        if ($filter === 'visible') {
            $formationsQuery->whereIn('id', $visibleFormations->pluck('id'));
        } elseif ($filter === 'hidden') {
            $formationsQuery->whereNotIn('id', $visibleFormations->pluck('id'));
        }

        // Apply sorting
        $validSortFields = ['title', 'money_amount', 'created_at', 'learners_count', 'lessons_count', 'total_duration_minutes'];
        if (in_array($sortBy, $validSortFields)) {
            $formationsQuery->orderBy($sortBy, $sortDirection);
        } else {
            $formationsQuery->orderBy('title', 'asc');
        }

        $allFormations = $formationsQuery->get();

        // Count different content types and calculate duration for each formation
        $allFormations->each(function ($formation) {
            $formation->video_count = $formation->lessons->where('lessonable_type', 'App\\Models\\VideoContent')->count();
            $formation->quiz_count = $formation->lessons->where('lessonable_type', 'App\\Models\\Quiz')->count();
            $formation->text_count = $formation->lessons->where('lessonable_type', 'App\\Models\\TextContent')->count();

            // Calculate total duration in minutes
            $totalDuration = 0;

            foreach ($formation->lessons as $lesson) {
                switch ($lesson->lessonable_type) {
                    case 'App\\Models\\VideoContent':
                        $totalDuration += $lesson->lessonable->duration_minutes ?? 0;
                        break;
                    case 'App\\Models\\TextContent':
                        $totalDuration += $lesson->lessonable->estimated_read_time ?? 0;
                        break;
                    case 'App\\Models\\Quiz':
                        // Estimate quiz duration: 2 minutes per question
                        $questionCount = $lesson->lessonable->quizQuestions()->count();
                        $totalDuration += max($questionCount * 2, 5); // Minimum 5 minutes per quiz
                        break;
                }
            }

            $formation->total_duration_minutes = $totalDuration;
        });

        return view('clean.organisateur.catalogue', compact(
            'team',
            'visibleFormations',
            'allFormations',
            'search',
            'filter',
            'sortBy',
            'sortDirection'
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

        return view('clean.organisateur.formation-show', compact(
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
        return view('clean.organisateur.users', compact('team'));
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

        return view('clean.organisateur.students', [
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

        return view('clean.organisateur.students-cost', array_merge([
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

        return view('clean.organisateur.student-report', array_merge([
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

        $pdf = Pdf::loadView('clean.organisateur.student-report-pdf', array_merge([
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

        $pdf = Pdf::loadView('clean.organisateur.student-report-pdf', array_merge([
            'team' => $team,
            'formation' => $formation,
            'student' => $student,
        ], $reportData));

        return $pdf->download('rapport-'.$student->name.'-'.now()->format('Y-m-d').'.pdf');
    }
}
