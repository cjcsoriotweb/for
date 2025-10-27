<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;
use Illuminate\View\View;

class AdminConfiguration extends Component
{
    /**
     * The team whose configuration page is displayed.
     */
    public $team;

    public function __construct($team)
    {
        $this->team = $team;
    }

    public function render(): View
    {
        return view('clean.admin.partials.configuration.index', [
            'team' => $this->team,
        ]);
    }
}
