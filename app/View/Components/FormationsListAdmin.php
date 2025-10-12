<?php

namespace App\View\Components;

use App\Models\Formation;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class FormationsList extends Component
{
    /**
     * Filtrage (props optionnels que tu peux passer depuis la vue)
     */
    public ?int $teamId;
    public ?string $search;
    public bool $onlyPublished;
    public int $limit;

    public function __construct(
        ?int $teamId = null,
        ?string $search = null,
        bool $onlyPublished = false,
        int $limit = 20
    ) {
        $this->teamId = $teamId ?? (Auth::user()?->current_team_id);
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
            ->latest('id');

        $formations = $q->limit($this->limit)->get();

        return view('components.formations-list', [
            'formations' => $formations,
        ]);
    }
}
