<?php

namespace App\Livewire\Eleve\Formation;

use App\Models\Formation;
use Livewire\Component;

class Autoplay extends Component
{
    public $formation;
    public $currentLesson;
    public $autoplay = false;
    public $countdown = 0;
    public $showCountdown = false;

    public function autoplayOn()
    {
        session(['autoplay' => true]);
        $this->autoplay = true;
    }

    public function autoplayOff()
    {
        session(['autoplay' => false]);
        $this->autoplay = false;
        $this->resetCountdown();
    }

    public function mount(Formation $formation, $currentLesson)
    {
        if (session()->has('autoplay')) {
            $this->autoplay = session()->get('autoplay');
            if ($this->autoplay) {
                $this->startCountdown();
            }
        }
    }

    public function startCountdown()
    {
        if ($this->autoplay) {
            $this->showCountdown = true;
            $this->countdown = 3;
        }
    }

    public function decrementCountdown()
    {
        if ($this->countdown > 0) {
            $this->countdown--;
        }

        if ($this->countdown <= 0) {
            $this->proceedToLesson();
        }
    }

    public function proceedToLesson()
    {
        $this->resetCountdown();
        return redirect()->route('eleve.lesson.show', [
            request()->route('team'),
            $this->formation,
            $this->currentLesson->chapter,
            $this->currentLesson
        ]);
    }

    private function resetCountdown()
    {
        $this->showCountdown = false;
        $this->countdown = 0;
    }

    public function render()
    {
        return view('livewire.eleve.formation.autoplay');
    }
}
