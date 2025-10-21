<?php

namespace App\Livewire\Eleve\Formation;

use App\Models\Formation;
use Livewire\Component;

class Autoplay extends Component
{
    public $formation;
    public $currentLesson;
    public $autoplay = false;

    public function autoplayOn()
    {
        session(['autoplay' => true]);
    }

    public function autoplayOff()
    {
        session(['autoplay' => false]);
    }
    public function mount(Formation $formation, $currentLesson)
    {
        if (session()->has('autoplay')) {
            $this->autoplay = session()->get('autoplay');
        }
    }
    public function render()
    {
        return view('livewire.eleve.formation.autoplay');
    }
}
