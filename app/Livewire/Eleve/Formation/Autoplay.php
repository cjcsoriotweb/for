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
        $this->startCountdown();
    }

    public function autoplayOff()
    {
        session(['autoplay' => false]);
        $this->autoplay = false;
        $this->resetCountdown();
    }

    public function mount(Formation $formation, $currentLesson)
    {
        $this->formation = $formation;
        $this->currentLesson = $currentLesson;

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
            $this->dispatch('countdownStarted');
        }
    }

    public function decrementCountdown()
    {
        if ($this->countdown > 0) {
            $this->countdown--;
        }

        if ($this->countdown <= 0) {
            $this->proceedToLesson();
        } else {
            $this->dispatch('countdownTick', $this->countdown);
        }
    }

    public function proceedToLesson()
    {
        $this->resetCountdown();
        $this->dispatch('autoplayRedirect');

        // Get team from current user or formation
        $team = auth()->user()->currentTeam ?? $this->formation->teams()->first();

        return redirect()->route('eleve.lesson.show', [
            $team,
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
