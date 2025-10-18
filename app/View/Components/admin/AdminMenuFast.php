<?php

namespace App\View\Components\admin;

use Illuminate\View\Component;
use Illuminate\View\View;

class AdminMenuFast extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public $team;
    public function __construct($team)
    {
        $this->team = $team;

        $this->team = $team;
    }
    public function render(): View
    {
        return view('clean.admin.partials.admin-menu-fast', [
            'team' => $this->team,
        ]);
    }
}