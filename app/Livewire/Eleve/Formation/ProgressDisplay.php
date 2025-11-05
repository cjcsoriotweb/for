<?php

namespace App\Livewire\Eleve\Formation;

use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProgressDisplay extends Component
{
    public Lesson $lesson;
    public $readPercent = 0;

    public function mount(Lesson $lesson)
    {
        $this->lesson = $lesson;
        $this->loadProgress();
    }

    public function loadProgress()
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();
        $lessonUser = $this->lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        if ($lessonUser && isset($lessonUser->pivot->read_percent)) {
            $this->readPercent = $lessonUser->pivot->read_percent;
        }
    }

    public function render()
    {
        // Recharger la progression à chaque rendu
        $this->loadProgress();

        return view('livewire.eleve.formation.progress-display');
    }
}
