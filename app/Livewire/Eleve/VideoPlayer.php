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

            Log::info('Loaded existing lesson progress', [
                'lesson_id' => $this->lesson->id,
                'user_id' => $user->id,
                'status' => $lessonUser->pivot->status,
                'watched_seconds' => $lessonUser->pivot->watched_seconds
            ]);
        }
    }

    private function ensureLessonStarted()
    {
        if (!Auth::check()) {
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
                'started_at' => now(),
                'last_activity_at' => now(),
            ]);

            Log::info('Lesson started for user', [
                'lesson_id' => $this->lesson->id,
                'user_id' => $user->id,
                'status' => 'in_progress'
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

    public function handleVideoEnded($data)
    {
        $this->videoCompleted = true;

        // Update lesson_user record with completion data
        $this->markLessonAsCompleted($data);

        // Show completion notification
        $this->showCompletionNotification = true;

        // Example: Log completion
        Log::info('Video completed', [
            'lesson_id' => $data['lessonId'],
            'lesson_content_id' => $data['lessonContentId'],
            'total_time' => $data['totalTime'],
            'user_id' => Auth::check() ? Auth::id() : null
        ]);

        // Emit event to parent component to handle redirect
        $this->dispatch('videoCompleted', [
            'lessonId' => $data['lessonId'],
            'teamId' => $this->team->id,
            'formationId' => $this->formation->id,
            'chapterId' => $this->chapter->id
        ]);
    }

    private function saveProgress($watchedSeconds, $completed = false)
    {
        if (!Auth::check()) {
            return;
        }

        list($minutes, $seconds) = explode(':', $watchedSeconds);
        $totalSeconds = ((int)$minutes * 60) + (int)$seconds;

        $user = Auth::user();

        // Find or create lesson_user record
        $lessonUser = $this->lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        if (!$lessonUser) {
            // Create new record if it doesn't exist
            $this->lesson->learners()->attach($user->id, [
                'watched_seconds' => $totalSeconds,
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

    private function markLessonAsCompleted($data)
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        // Find or create lesson_user record and mark as completed
        $lessonUser = $this->lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        if (!$lessonUser) {
            // Create completed record if it doesn't exist
            $this->lesson->learners()->attach($user->id, [
                'watched_seconds' => $data['totalTime'],
                'status' => 'completed',
                'started_at' => now(),
                'last_activity_at' => now(),
                'completed_at' => now(),
            ]);

            Log::info('Lesson completed - new record created', [
                'lesson_id' => $data['lessonId'],
                'user_id' => $user->id,
                'status' => 'completed',
                'watched_seconds' => $data['totalTime']
            ]);
        } else {
            // Update existing record to completed
            $this->lesson->learners()->updateExistingPivot($user->id, [
                'status' => 'completed',
                'watched_seconds' => $data['totalTime'],
                'completed_at' => now(),
                'last_activity_at' => now(),
            ]);

            Log::info('Lesson completed - existing record updated', [
                'lesson_id' => $data['lessonId'],
                'user_id' => $user->id,
                'status' => 'completed',
                'watched_seconds' => $data['totalTime']
            ]);
        }
    }

    protected $listeners = [
        'videoTimeUpdate' => 'handleVideoTimeUpdate',
        'videoEnded' => 'handleVideoEnded',
        'hide-save-notification' => 'hideSaveNotification'
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
