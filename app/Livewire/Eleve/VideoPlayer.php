<?php

namespace App\Livewire\Eleve;

use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;

class VideoPlayer extends Component
{
    public $team;
    public $formation;
    public $chapter;
    public $lesson;
    public $lessonContent;

    // Tracking
    public $resumeTime = 0;
    public $duration = 0;
    public $currentTime = 0;
    public $watchedPercentage = 0;
    public $videoCompleted = false;
    public $currentTimePlayer;

    // UI
    public $showCompletionNotification = false;
    public $isPlaying = false;

    //Page

    public $currentPage = "vplayer";
    // vplayer
    // eplayer


    public function mount(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, $lessonContent)
    {
        
        
        $this->team = $team;
        $this->formation = $formation;
        $this->chapter = $chapter;
        $this->lesson = $lesson;
        $this->lessonContent = $lessonContent;

        $this->loadExistingProgress();
    }

    private function loadExistingProgress(): void
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        $lessonUser = $this->lesson->learners()->where('user_id', $user->id)->first();

        if ($lessonUser) {
            $this->resumeTime = (int) ($lessonUser->pivot->watched_seconds ?? 0);
            $this->videoCompleted = ($lessonUser->pivot->status ?? null) === 'completed';
        }
    }

    public function playVideo(): void
    {
        $this->isPlaying = true;
        $this->dispatch('video-play');
    }

    public function pauseVideo(): void
    {
        $this->isPlaying = false;
        $this->dispatch('video-pause');
    }

    public function togglePlayback(): void
    {
        $this->isPlaying ? $this->pauseVideo() : $this->playVideo();
    }

    public function seekBy(int $seconds): void
    {
        $seconds = max(-3600, min(3600, $seconds));
        $this->dispatch('video-seek', $seconds);
    }

    public function handleVideoProgress(float $currentTime = 0, float $duration = 0): void
    {
        $this->currentTime = max(0, $currentTime);
        $this->resumeTime = (int) $this->currentTime;

        if ($duration > 0) {
            $this->duration = $duration;
        }

        if ($this->duration > 0) {
            $this->watchedPercentage = min(100, max(0, ($this->currentTime / $this->duration) * 100));
        }
    }

    #[On('ended')]
    public function ended()
    {
        $this->currentPage = "eplayer";
    }

    public function replay(){
        $this->js('window.location.reload()');

    }
    public function completed()
    {
        if (Auth::check()) {
            $lessonUser = $this->lesson->learners()->where('user_id', Auth::id())->first();

            if ($lessonUser) {
                $this->lesson->learners()->updateExistingPivot(Auth::id(), ['status' => 'completed']);
            }
        }
        $this->js('window.location.reload()');

    }

    #[On('post')]
    public function post(int $data)
    {
        $this->currentTimePlayer = $data;

        // Si LessonUser exist dd(ici)
        if (Auth::check()) {
            $lessonUser = $this->lesson->learners()->where('user_id', Auth::id())->first();

            if ($lessonUser) {
                if ($lessonUser->pivot->watched_seconds < $data) {
                    $this->lesson->learners()->updateExistingPivot(Auth::id(), ['watched_seconds' => $data]);
                    $this->dispatch('updated', data: $data);
                }
            }
        }
        $this->skipRender();
    }

    public function render()
    {

        if($this->currentPage === "vplayer"){
        return view('livewire.eleve.video.video-player');

        } else {
        return view('livewire.eleve.video.video-end');

        }
    }
}
