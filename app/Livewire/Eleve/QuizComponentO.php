<?php

namespace App\Livewire\Eleve;

use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use App\Models\Team;
use App\Models\User;
use App\Services\Formation\StudentFormationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizComponentO extends Component
{


    public $quiz;
    public $questions;
    public $lesson;
    public $team;
    public $formation;
    public $chapter;

    public $currentQuiz = 0;



    public $heatbeat = 0;
    public $step = 0;
    public $countdown = 10;






    // Engine
    public function componentEngine($team, $formation, $chapter, $lesson)
    {
        if ($this->step == 0) {

            $user = Auth::user();
            $team = $team;
            $formation = $formation;
            $chapter = $chapter;
            $lesson = $lesson;

            if (!$this->studentFormationService()->isEnrolledInFormation($user, $formation, $team)) {
                abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
            }

            // Vérification que la leçon est bien un quiz
            if ($lesson->lessonable_type !== Quiz::class) {
                abort(404, 'Quiz non trouvé.');
            }

            $this->step = 1;


            // Initialisation des propriétés
            $this->team = $team;
            $this->formation = $formation;
            $this->chapter = $chapter;
            $this->lesson = $lesson;
            $this->quiz = $lesson->lessonable;

            // Récupérer les questions du quiz
            $this->questions = $this->quiz->quizQuestions()->with('quizChoices')->get();
            dd($this->quiz);

            return view('livewire.eleve.quiz.loading-module');
        }

        if ($this->step == 1) {
            return view('livewire.eleve.quiz.init-quiz');
        }

        if ($this->step == 2) {
            if ($this->countdown >= 1) {
                return view('livewire.eleve.quiz.question');
            } else {
                return view('livewire.eleve.quiz.timeleft');
            }
        }

        if ($this->step == 3) {
            return view('livewire.eleve.quiz.reponse');
        }
    }

    public function validateQuiz()
    {
        $this->step = 3;
    }
    public function setStep($int)
    {
        if ($int == 2) { // Launch Quiz
            $this->countdown = 100;
        }
        $this->step = $int;
    }
    public function dIntCountdown()
    {
        $this->countdown -= 1;
    }
    public function heartBeat()
    {
        $this->heatbeat += 1;
    }

    private function studentFormationService()
    {
        return app(StudentFormationService::class);
    }


    public function mount($team, $formation, $chapter, $lesson)
    {
        $this->team = $team;
        $this->formation = $formation;
        $this->chapter = $chapter;
        $this->lesson = $lesson;
    }
    // Affichage du composant
    public function render()
    {

        return $this->componentEngine($this->team, $this->formation, $this->chapter, $this->lesson);
    }
}
