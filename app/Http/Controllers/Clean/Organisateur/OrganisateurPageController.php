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
            return redirect()->route('organisateur.formations.students.index', [$team, $formation])
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
            return redirect()->route('organisateur.formations.students.index', [$team, $formation])
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

        return $pdf->stream('rapport-' . $student->name . '.pdf');
    }

    public function studentReportPdfDownload(Team $team, Formation $formation, User $student)
    {
        if (! $this->organisateurService->formationIsVisibleToTeam($team, $formation)) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Formation non accessible.');
        }

        if (! $this->organisateurService->studentIsEnrolledInFormation($formation, $student)) {
            return redirect()->route('organisateur.formations.students.index', [$team, $formation])
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

        return $pdf->download('rapport-' . $student->name . '-' . now()->format('Y-m-d') . '.pdf');
    }
}
