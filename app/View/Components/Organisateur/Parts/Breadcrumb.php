<?php

namespace App\View\Components\Organisateur\Parts;

use App\Models\Formation;
use App\Models\Team;
use App\Models\User;
use Illuminate\View\Component;
use Illuminate\View\View;

class Breadcrumb extends Component
{
    public Team $team;

    public ?Formation $formation;

    public ?User $student;

    public ?string $currentPage;

    public function __construct(
        Team $team,
        ?Formation $formation = null,
        ?User $student = null,
        string $currentPage = ''
    ) {
        $this->team = $team;
        $this->formation = $formation;
        $this->student = $student;
        $this->currentPage = $currentPage;
    }

    public function render(): View
    {
        return view('in-application.organisateur.parts.breadcrumb', [
            'team' => $this->team,
            'formation' => $this->formation,
            'student' => $this->student,
            'currentPage' => $this->currentPage,
        ]);
    }
}
