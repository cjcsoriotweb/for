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
    public $quizzTime = [
        'timeLeft' => false,
        'countdown' => 10,
    ];

    public function CountDownDInt()
    {
        $this->quizzTime['countdown'] -= 1;
        if (!$this->quizzTime['countdown']) {
            $this->quizzTime['timeLeft'] = true;
        }
    }



    // Affichage du composant
    public function render()
    {
        if ($this->quizzTime['timeLeft']) {
            return view('livewire.eleve.quiz.timeleft');
        }
        return view('livewire.eleve.quiz.quiz-component-o');
    }
}
