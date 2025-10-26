<?php

namespace App\Livewire\Eleve;

use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VideoPlayer extends Component
{
    public $team;

    public $formation;

    public $chapter;

    public $lesson;

    public $lessonContent;

    // Video tracking properties
    public $currentTime = 0;

    public $duration = 0;

    public $watchedPercentage = 0;

    public $videoCompleted = false;

    // Notification properties
    public $lastSavedTime = null;

    public $showSaveNotification = false;

    public $showCompletionNotification = false;

    public function mount(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, $lessonContent)
    {
        $this->team = $team;
        $this->formation = $formation;
        $this->chapter = $chapter;
        $this->lesson = $lesson;
        $this->lessonContent = $lessonContent;

        // Load existing progress if user is authenticated
        $this->loadExistingProgress();
    }

    private function loadExistingProgress()
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();

        // Get existing lesson_user record
        $lessonUser = $this->lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        if ($lessonUser) {
            $this->currentTime = $lessonUser->pivot->watched_seconds;
            $this->videoCompleted = $lessonUser->pivot->status === 'completed';
        }
    }

    private function ensureLessonStarted()
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();

        // Check if lesson_user record exists
        $lessonUser = $this->lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        if (! $lessonUser) {
            // Create lesson_user record with in_progress status
            $this->lesson->learners()->attach($user->id, [
                'watched_seconds' => 0,
                'status' => 'in_progress',
                'started_at' => now(),
                'last_activity_at' => now(),
            ]);
        }
    }

    public function handleVideoTimeUpdate($currentTime)
    {
        $this->currentTime = $currentTime;

        // Ensure lesson is started when user begins watching
        $this->ensureLessonStarted();

        // Update watched_seconds in database (sent every 5 seconds from JavaScript)
        $this->saveProgress($currentTime, false); // Explicitly set to in_progress

        // Show save notification
        $this->lastSavedTime = $currentTime;
        $this->showSaveNotification = true;

        // Hide notification after 3 seconds
        $this->dispatch('hide-save-notification');
    }

    public function handleVideoEnded()
    {
        $this->videoCompleted = true;

        // Update lesson_user record with completion data
        $this->markLessonAsCompleted();

        // Show completion notification
        $this->showCompletionNotification = true;
        $this->dispatch('leave', true);
    }

    private function saveProgress($watchedSeconds, $completed = false)
    {
        if (! Auth::check()) {
            return;
        }

        [$minutes, $seconds] = explode(':', $watchedSeconds);
        $totalSeconds = ((int) $minutes * 60) + (int) $seconds;

        $user = Auth::user();

        // Find or create lesson_user record
        $lessonUser = $this->lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        if (! $lessonUser) {
            // Create new record if it doesn't exist
            $this->lesson->learners()->attach($user->id, [
                'watched_seconds' => $watchedSeconds,
                'status' => $completed ? 'completed' : 'in_progress',
                'started_at' => now(),
                'last_activity_at' => now(),
                'completed_at' => $completed ? now() : null,
            ]);
        } else {
            // Update existing record

            $this->lesson->learners()->updateExistingPivot($user->id, [
                'watched_seconds' => $totalSeconds,
                'status' => $completed ? 'completed' : 'in_progress',
                'last_activity_at' => now(),
                'completed_at' => $completed ? now() : null,
            ]);
        }
    }

    private function markLessonAsCompleted()
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();

        // Find or create lesson_user record and mark as completed
        $lessonUser = $this->lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        if ($lessonUser) {
            $this->lesson->learners()->updateExistingPivot($user->id, [
                'status' => 'completed',
                'last_activity_at' => now(),
                'completed_at' => now(),
            ]);

            // Mettre à jour la progression globale de la formation
            $this->updateFormationProgress($user, $this->formation);
        }
    }

    /**
     * Mettre à jour la progression globale d'une formation
     */
    private function updateFormationProgress($user, $formation): void
    {
        // Récupérer tous les chapitres avec leurs leçons et la progression de l'utilisateur
        $chapters = $formation->chapters()
            ->with(['lessons' => function ($query) use ($user) {
                $query->with(['learners' => function ($learnerQuery) use ($user) {
                    $learnerQuery->where('user_id', $user->id);
                }]);
            }])
            ->get();

        // Calculer la progression basée sur les leçons terminées
        $totalLessons = $chapters->pluck('lessons')->flatten()->count();
        $completedLessons = 0;

        foreach ($chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                $lessonProgress = $lesson->learners->first();
                if ($lessonProgress && $lessonProgress->pivot->status === 'completed') {
                    $completedLessons++;
                }
            }
        }

        $progressPercent = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

        // Mettre à jour le current_lesson_id
        $this->updateCurrentLessonId($user, $formation);

        // Mettre à jour la progression de la formation
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'last_seen_at' => now(),
                'status' => $progressPercent >= 100 ? 'completed' : 'in_progress',
            ],
        ]);
    }

    /**
     * Mettre à jour le current_lesson_id pour pointer vers la prochaine leçon non terminée
     */
    private function updateCurrentLessonId($user, $formation): void
    {
        // Récupérer la formation avec tous les chapitres et leçons ordonnés
        $formationWithLessons = $formation->load([
            'chapters' => function ($query) {
                $query->orderBy('position')
                    ->with(['lessons' => function ($lessonQuery) {
                        $lessonQuery->orderBy('position');
                    }]);
            },
        ]);

        $nextLessonId = null;

        // Parcourir tous les chapitres et leçons pour trouver la première non terminée
        foreach ($formationWithLessons->chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                // Vérifier si cette leçon est terminée
                $lessonProgress = $lesson->learners()
                    ->where('user_id', $user->id)
                    ->first();

                if (! $lessonProgress || $lessonProgress->pivot->status !== 'completed') {
                    // Cette leçon n'est pas terminée, c'est la suivante
                    $nextLessonId = $lesson->id;
                    break 2; // Sortir des deux boucles
                }
            }
        }

        // Mettre à jour le current_lesson_id dans formation_user
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'current_lesson_id' => $nextLessonId,
                'last_seen_at' => now(),
            ],
        ]);
    }

    protected $listeners = [
        'videoTimeUpdate' => 'handleVideoTimeUpdate',
        'videoEnded' => 'handleVideoEnded',
        'hide-save-notification' => 'hideSaveNotification',
    ];

    public function hideSaveNotification()
    {
        $this->showSaveNotification = false;
    }

    public function render()
    {
        return view('livewire.eleve.video-player');
    }
}
