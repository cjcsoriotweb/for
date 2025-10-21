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
        'loading' => true,
        'timeLeft' => false,
        'search' => false,
        'quiz' => false,
        'countdown' => 10,
    ];

    public function CountDownDInt()
    {
        $this->quizzTime['countdown'] -= 1;
        if (!$this->quizzTime['countdown']) {
            $this->quizzTime['timeLeft'] = true;
        }
    }
    public function searchNewQuiz()
    {
        $this->quizzTime['search'] = true;
    }
    public function getQuiz()
    {
        $this->quizzTime['search'] = false;
        $this->quizzTime['loading'] = false;
        $this->quizzTime['quiz'] = true;
    }



    // Affichage du composant
    public function render()
    {
        return view('livewire.eleve.quiz.reponse');

        if ($this->quizzTime['timeLeft']) {
            return view('livewire.eleve.quiz.timeleft');
        }
        if ($this->quizzTime['loading']) {
            return view('livewire.eleve.quiz.start');
        }
        if ($this->quizzTime['quiz']) {
            return view('livewire.eleve.quiz.question');
        }
        if ($this->quizzTime['search']) {
            return view('livewire.eleve.quiz.question');
        }
    }
}
