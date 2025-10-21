<?php

namespace App\Livewire\Eleve\Formation;

use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Readtext extends Component
{
    public $elapsedTime = 0;
    public $requiredTime = 0;
    public $canProceed = false;
    public $startTime;
    public $isActive = false;
    public $lesson;
    public $watchedSeconds = 0;

    public function mount($requiredTime = 0, Lesson $lesson = null)
    {
        // Convert estimated_read_time from minutes to seconds
        $this->requiredTime = $requiredTime * 60;
        $this->lesson = $lesson;

        // Load existing progress if lesson and user are available
        $this->loadExistingProgress();

        // Set start time based on existing progress
        if ($this->watchedSeconds > 0) {
            // Resume from existing time - calculate when they started
            $this->startTime = now()->timestamp - $this->watchedSeconds;
        } else {
            $this->startTime = now()->timestamp;
        }

        // Start timer automatically
        $this->isActive = true;
    }

    private function loadExistingProgress()
    {
        if (!Auth::check() || !$this->lesson) {
            return;
        }

        $user = Auth::user();

        // Get existing lesson_user record
        $lessonUser = $this->lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        if ($lessonUser) {
            $this->watchedSeconds = $lessonUser->pivot->watched_seconds;
            $this->elapsedTime = $this->watchedSeconds;

            Log::info('Loaded existing lesson reading progress', [
                'lesson_id' => $this->lesson->id,
                'user_id' => $user->id,
                'watched_seconds' => $this->watchedSeconds
            ]);
        }
    }

    private function ensureLessonStarted()
    {
        if (!Auth::check() || !$this->lesson) {
            return;
        }

        $user = Auth::user();

        // Check if lesson_user record exists
        $lessonUser = $this->lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        if (!$lessonUser) {
            // Create lesson_user record with in_progress status
            $this->lesson->learners()->attach($user->id, [
                'watched_seconds' => 0,
                'status' => 'in_progress',
                'last_activity_at' => now(),
            ]);

            Log::info('Lesson reading started for user', [
                'lesson_id' => $this->lesson->id,
                'user_id' => $user->id,
                'status' => 'in_progress'
            ]);
        }
    }

    public function saveProgress()
    {
        if (!Auth::check() || !$this->lesson) {
            return;
        }

        $this->ensureLessonStarted();

        $user = Auth::user();

        // Update watched_seconds in database
        $this->lesson->learners()->updateExistingPivot($user->id, [
            'watched_seconds' => $this->elapsedTime,
            'last_activity_at' => now(),
        ]);

        $this->watchedSeconds = $this->elapsedTime;
    }

    // Timer starts automatically in mount() method

    public function checkTimer()
    {
        if ($this->isActive) {
            $currentTime = now()->timestamp;
            $this->elapsedTime = $currentTime - $this->startTime;

            // Save progress every second
            $this->saveProgress();

            if ($this->elapsedTime >= $this->requiredTime) {
                $this->canProceed = true;
                $this->isActive = false;

                // Mark lesson as completed when timer finishes
                $this->markLessonAsCompleted();
            }
        }
    }

    private function markLessonAsCompleted()
    {
        if (!Auth::check() || !$this->lesson) {
            return;
        }

        $user = Auth::user();

        // Update lesson_user record with completion data
        $this->lesson->learners()->updateExistingPivot($user->id, [
            'status' => 'completed',
            'completed_at' => now(),
            'watched_seconds' => $this->elapsedTime,
        ]);

        Log::info('Lesson reading completed', [
            'lesson_id' => $this->lesson->id,
            'user_id' => $user->id,
            'total_watched_seconds' => $this->elapsedTime
        ]);
    }

    public function getRemainingTimeDisplayProperty()
    {
        $currentTime = now()->timestamp;
        $elapsed = $currentTime - $this->startTime;
        $remainingSeconds = max(0, $this->requiredTime - $elapsed);

        return $this->formatTime($remainingSeconds);
    }

    private function formatTime($seconds)
    {
        if ($seconds <= 0) {
            return '0m00s';
        }

        $mins = floor($seconds / 60);
        $secs = str_pad($seconds % 60, 2, '0', STR_PAD_LEFT);

        return "{$mins}m{$secs}s";
    }

    public function getProgressPercentageProperty()
    {
        if (!$this->startTime || $this->requiredTime <= 0) {
            return 0;
        }

        $elapsed = now()->timestamp - $this->startTime;
        $progress = min(max(($this->requiredTime - $elapsed) / $this->requiredTime * 100, 0), 100);

        return round($progress, 1);
    }

    public function render()
    {
        return view('livewire.eleve.formation.readtext');
    }
}
