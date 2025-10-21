<?php

namespace App\Livewire\Eleve;

use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        if (!Auth::check()) {
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

    public function handleVideoTimeUpdate($data)
    {
        $this->currentTime = $data['currentTime'];
        $this->duration = $data['duration'];
        $this->watchedPercentage = round($data['percentage'], 2);

        // Save progress to database periodically
        $this->saveProgress($data['currentTime']);
    }

    public function handleVideoEnded($data)
    {
        $this->videoCompleted = true;

        // Save final progress and mark as completed
        $this->saveProgress($data['totalTime'], true);

        // Example: Log completion
        Log::info('Video completed', [
            'lesson_id' => $data['lessonId'],
            'lesson_content_id' => $data['lessonContentId'],
            'total_time' => $data['totalTime'],
            'user_id' => auth()->check() ? auth()->id() : null
        ]);

        // Redirect to lesson page to update lesson status and show next content
        return redirect()->route('eleve.lesson.show', [
            'team' => $this->team->id,
            'formation' => $this->formation->id,
            'chapter' => $this->chapter->id,
            'lesson' => $data['lessonId']
        ]);
    }

    private function saveProgress($watchedSeconds, $completed = false)
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        // Find or create lesson_user record
        $lessonUser = $this->lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        if (!$lessonUser) {
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
                'watched_seconds' => $watchedSeconds,
                'status' => $completed ? 'completed' : 'in_progress',
                'last_activity_at' => now(),
                'completed_at' => $completed ? now() : null,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.eleve.video-player');
    }
}
