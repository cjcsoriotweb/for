<?php

namespace App\Http\Controllers\Application\Eleve;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnableFormationRequest;
use App\Models\Formation;
use App\Models\Team;
use App\Services\FormationEnrollmentService;
use App\Services\FormationVisibilityService;
use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizChoice;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EleveController extends Controller
{
    private FormationVisibilityService $visibilityService;
    private FormationEnrollmentService $enrollmentService;

    public function __construct(
        FormationVisibilityService $visibilityService,
        FormationEnrollmentService $enrollmentService
    ) {
        $this->visibilityService = $visibilityService;
        $this->enrollmentService = $enrollmentService;
    }

    public function index(Team $team)
    {
        return view('application.eleve.index', compact('team'));
    }

    public function formationIndex(Team $team)
    {
        $formationsByTeam = $team->formationsByTeam()->get();

        return view('application.eleve.formationsList', compact('team', 'formationsByTeam'));
    }

    public function formationPreview(Team $team, Formation $formation)
    {
        return view('application.eleve.formationPreview', compact('team', 'formation'));
    }

    public function formationContinue(Team $team, Formation $formation)
    {
        return view('application.eleve.formationContinue', compact('team', 'formation'));
    }

    public function formationShow(Team $team, Formation $formation)
    {
        // Vérifier que l'utilisateur peut accéder à cette formation
        if (!$this->visibilityService->isFormationVisibleForTeam($formation, $team)) {
            abort(403, 'Formation non accessible.');
        }

        return view('application.eleve.formationShow', compact('team', 'formation'));
    }

    public function formationEnable(EnableFormationRequest $request, Team $team, Formation $formation)
    {
        // La validation et l'autorisation se font dans EnableFormationRequest

        // Vérifier si déjà inscrit
        if ($this->enrollmentService->isUserEnrolled($formation)) {
            return redirect()->route('application.eleve.formations.continue', [$team, $formation])
                ->with('info', 'Vous êtes déjà inscrit à cette formation.');
        }

        // Inscrire l'utilisateur à la formation et débiter les fonds
        $success = $this->enrollmentService->enrollUser($formation, $team);

        if (!$success) {
            return redirect()->route('application.eleve.formations.preview', [$team, $formation])
                ->with('error', "Les fonds de l'équipe sont insuffisants pour commencer cette formation (requis : {$formation->money_amount}€).");
        }

        return redirect()->route('application.eleve.formations.continue', [$team, $formation])
            ->with('success', "La formation '{$formation->title}' a été activée.");
    }

    /**
     * Vérifie si l'équipe a les fonds nécessaires pour commencer une formation
     */
    private function canTeamAffordFormation(Team $team, Formation $formation): bool
    {
        return $team->money >= $formation->money_amount;
    }

    /**
     * Débite les fonds de l'équipe pour une formation
     */
    private function debitTeamFunds(Team $team, Formation $formation): void
    {
        $team->decrement('money', $formation->money_amount);
    }

    public function formationLesson(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        // Vérifications de sécurité
        if (!$this->visibilityService->isFormationVisibleForTeam($formation, $team)) {
            abort(403, 'Formation non accessible.');
        }

        if (!$this->enrollmentService->isUserEnrolled($formation)) {
            abort(403, 'Vous devez être inscrit à cette formation.');
        }

        // Marquer la leçon comme commencée si pas encore fait
        $lessonProgress = $lesson->learners()->where('users.id', auth()->id())->first();

        if (!$lessonProgress) {
            $lesson->learners()->attach(auth()->id(), [
                'team_id' => $team->id,
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }

        // Contenus de la leçon (vidéo ou texte)
        $videoContent = $lesson->videoContent; // Relation à définir
        $textContent = $lesson->textContent;   // Relation à définir

        return view('application.eleve.formationLesson', compact('team', 'formation', 'chapter', 'lesson', 'videoContent', 'textContent'));
    }

    public function formationLessonComplete(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        // Vérifications
        if (!$this->enrollmentService->isUserEnrolled($formation)) {
            abort(403);
        }

        $lessonProgress = $lesson->learners()->where('users.id', auth()->id())->first()?->pivot;

        if (!$lessonProgress) {
            abort(403);
        }

        // Marquer la leçon comme complétée
        $lesson->learners()->updateExistingPivot(auth()->id(), [
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Mettre à jour la progression de la formation
        $this->updateFormationProgress($formation);

        // Rediriger vers la leçon suivante ou la formation continue
        $nextLesson = $this->getNextLesson($lesson);

        if ($nextLesson) {
            return redirect()->route('application.eleve.formations.lesson', [$team, $formation, $nextLesson->chapter, $nextLesson])
                ->with('success', 'Leçon terminée ! Continuez avec la suivante.');
        }

        return redirect()->route('application.eleve.formations.continue', [$team, $formation])
            ->with('success', 'Leçon terminée !');
    }

    public function formationQuiz(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, Quiz $quiz)
    {
        // Vérifications
        if (!$this->enrollmentService->isUserEnrolled($formation)) {
            abort(403);
        }

        // Vérifier si la leçon est complétée pour accéder au quiz
        $lessonProgress = $lesson->learners()->where('users.id', auth()->id())->first()?->pivot;

        if (!$lessonProgress || $lessonProgress->status !== 'completed') {
            return redirect()->route('application.eleve.formations.lesson', [$team, $formation, $chapter, $lesson])
                ->with('warning', 'Vous devez d\'abord terminer la leçon.');
        }

        $questions = $quiz->quizQuestions()->with('quizChoices')->get();

        return view('application.eleve.formationQuiz', compact('team', 'formation', 'chapter', 'lesson', 'quiz', 'questions'));
    }

    public function formationQuizSubmit(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, Quiz $quiz, Request $request)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|integer',
        ]);

        // Calculer le score
        $questions = $quiz->quizQuestions()->with('quizChoices')->get();
        $score = 0;
        $total = $questions->count();

        $answers = [];
        foreach ($questions as $question) {
            $userChoiceId = $validated['answers'][$question->id] ?? null;
            $correctChoice = $question->quizChoices->where('is_correct', true)->first();

            $isCorrect = $correctChoice && $correctChoice->id == $userChoiceId;
            if ($isCorrect) {
                $score++;
            }

            $answers[] = [
                'question_id' => $question->id,
                'choice_id' => $userChoiceId,
                'is_correct' => $isCorrect,
            ];
        }

        // Enregistrer la tentative
        $attempt = DB::table('quiz_attempts')->insert([
            'quiz_id' => $quiz->id,
            'user_id' => auth()->id(),
            'lesson_id' => $lesson->id,
            'score' => $score,
            'total_questions' => $total,
            'completed_at' => now(),
        ]);

        // Enregistrer les réponses
        foreach ($answers as $answer) {
            DB::table('quiz_answers')->insert([
                'quiz_attempt_id' => $attempt,
                'question_id' => $answer['question_id'],
                'choice_id' => $answer['choice_id'],
                'is_correct' => $answer['is_correct'],
            ]);
        }

        // Mettre à jour la progression de la leçon
        $lesson->learners()->updateExistingPivot(auth()->id(), [
            'best_score' => DB::table('lesson_user')->where('lesson_id', $lesson->id)->where('user_id', auth()->id())->first()?->best_score > $score ? DB::table('lesson_user')->where('lesson_id', $lesson->id)->where('user_id', auth()->id())->first()->best_score : $score,
        ]);

        return redirect()->route('application.eleve.formations.continue', [$team, $formation])
            ->with('success', "Quiz terminé ! Score : {$score}/{$total}");
    }

    private function getNextLesson(Lesson $currentLesson)
    {
        // Trouver la leçon suivante dans le chapitre
        $next = Lesson::where('chapter_id', $currentLesson->chapter_id)
            ->where('position', '>', $currentLesson->position)
            ->orderBy('position')
            ->first();

        if ($next) {
            return $next;
        }

        // Si pas de leçon suivante dans le chapitre, passer au chapitre suivant
        $nextChapter = Chapter::where('formation_id', $currentLesson->chapter->formation_id)
            ->where('position', '>', $currentLesson->chapter->position)
            ->orderBy('position')
            ->first();

        if ($nextChapter) {
            return $nextChapter->lessons()->orderBy('position')->first();
        }

        return null;
    }

    private function updateFormationProgress(Formation $formation)
    {
        $user = auth()->user();

        $totalLessons = $formation->lessons->count();
        $completedLessons = $formation->learners()
            ->where('formation_user.status', 'completed')
            ->where('users.id', $user->id)
            ->count();

        if ($totalLessons > 0) {
            $progressPercent = round(($completedLessons / $totalLessons) * 100);
        } else {
            $progressPercent = 0;
        }

        $formation->learners()->updateExistingPivot($user->id, [
            'progress_percent' => $progressPercent,
            'last_seen_at' => now(),
        ]);
    }
}
