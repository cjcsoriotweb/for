<?php

namespace App\View\Components;

use App\Models\Formation;
use App\Models\Team;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class FormationsList extends Component
{
    /**
     * Filtrage (props optionnels que tu peux passer depuis la vue)
     */
    public Team $team;
    public ?string $search;
    public bool $onlyPublished;
    public int $limit;

    public function __construct(
        Team $team,
        ?string $search = null,
        bool $onlyPublished = false,
        int $limit = 20
    ) {
        $this->team = $team;
        if (!$team) {
            throw new \InvalidArgumentException("Le composant FormationsList as besoin de la team pour fonctionner.");
        }
        $this->search = $search;
        $this->onlyPublished = $onlyPublished;
        $this->limit = max(1, min($limit, 100)); // borne 1..100
    }

    public function render(): View|Closure|string
    {
        $q = Formation::query()
            ->when(($s = trim((string) $this->search)) !== '', function ($qq) use ($s) {
                $qq->where(function ($w) use ($s) {
                    $w->where('title', 'like', "%{$s}%")
                        ->orWhere('summary', 'like', "%{$s}%");
                });
            })
            ->when($this->onlyPublished, fn($qq) => $qq->where('published', true))
            ->withCount('lessons')
            ->visibleForTeam($this->team->id)
            ->latest('id');


        $formations = $q->limit($this->limit)->get();

        return view('components.formations-list', [
            'formations' => $formations,
        ]);
    }
}
