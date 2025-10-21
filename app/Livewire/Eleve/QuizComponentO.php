<?php

namespace App\Livewire\Eleve;

use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\Team;
use App\Models\User;
use App\Services\Formation\StudentFormationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizComponentO extends Component
{
    public $countdown = 10;
    public $countdownEnd = false;

    public function countdownPoll()
    {
        $this->countdown -= 1;
        if ($this->countdown <= 0) {
            $this->countdownEnd = true;
        }
    }
    // Affichage du composant
    public function render()
    {
        return view('livewire.eleve.quiz-component-o');
    }
}
