<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class ApplicationLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public $team;

    public function __construct($team)
    {
        $this->team = $team;
    }

    public function render(): View
    {
        return view('layouts.application', [
            'team' => $this->team,
        ]);
    }
}
