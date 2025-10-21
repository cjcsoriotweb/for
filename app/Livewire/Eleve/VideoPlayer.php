<?php

namespace App\Livewire\Eleve;

use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\Team;
use App\Models\User;
use App\Models\VideoContent;
use App\Services\Formation\StudentFormationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VideoPlayer extends Component
{
    public Team $team;
    public Formation $formation;
    public Chapter $chapter;
    public Lesson $lesson;
    public VideoContent $lessonContent;

    public $currentTime = 0;
    public $duration = 0;
    public $progressPercent = 0;
    public $isPlaying = false;
    public $isCompleted = false;
    public $showSuccessMessage = false;
    public $showManualButton = false;

    public function mount()
    {
        $this->updateDebugIndicators();
    }

    public function updatedCurrentTime($value)
    {
        $this->progressPercent = $this->duration > 0 ? round(($value / $this->duration) * 100) : 0;
        $this->updateDebugIndicators();

        // Sauvegarder la progression automatiquement
        $this->saveProgress();
    }

    public function saveProgress()
    {
        $user = Auth::user();

        // Sauvegarder la progression via le service existant
        $this->lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'read_percent' => $this->progressPercent,
                'last_activity_at' => now(),
            ],
        ]);
    }



    private function updateFormationProgress()
    {
        $user = Auth::user();
        $studentFormationService = app(StudentFormationService::class);

        // Recalculer la progression globale
        $progress = $studentFormationService->getStudentProgress($user, $this->formation);

        // Mettre à jour la progression de la formation
        $this->formation->learners()->syncWithoutDetaching([
            $user->id => [
                'progress_percent' => $progress['percentage'] ?? 0,
                'last_seen_at' => now(),
                'status' => ($progress['percentage'] ?? 0) >= 100 ? 'completed' : 'in_progress',
            ],
        ]);
    }

    public function updateDebugIndicators()
    {
        $this->dispatch('update-debug-indicators', [
            'currentTime' => $this->formatTime($this->currentTime),
            'duration' => $this->formatTime($this->duration),
            'progress' => $this->progressPercent . '%',
            'isPlaying' => $this->isPlaying,
            'isCompleted' => $this->isCompleted,
        ]);
    }

    private function formatTime($seconds)
    {
        $mins = floor($seconds / 60);
        $secs = floor($seconds % 60);
        return sprintf('%d:%02d', $mins, $secs);
    }

    protected $listeners = [
        'markVideoAsCompleted' => 'markAsCompleted',
        'echo:video-progress' => 'handleVideoProgress'
    ];

    public function markAsCompleted()
    {
        if ($this->isCompleted) {
            return;
        }

        $this->isCompleted = true;
        $this->showSuccessMessage = true;

        $user = Auth::user();

        // Marquer la leçon comme terminée
        $this->lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'completed_at' => now(),
                'last_activity_at' => now(),
                'status' => 'completed',
            ],
        ]);

        // Mettre à jour la progression globale de la formation
        $this->updateFormationProgress();

        // Rediriger après 3 secondes
        $this->dispatch('redirect-after-delay', [
            'url' => route('eleve.formation.show', [$this->team, $this->formation]),
            'delay' => 3000
        ]);
    }

    public function handleVideoProgress($data)
    {
        $this->currentTime = $data['currentTime'] ?? 0;
        $this->progressPercent = $data['progressPercent'] ?? 0;
        $this->isPlaying = $data['isPlaying'] ?? false;

        $this->updateDebugIndicators();
    }

    public function render()
    {
        return view('livewire.eleve.video-player');
    }
}
