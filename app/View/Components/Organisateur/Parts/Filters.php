<?php

namespace App\View\Components\Organisateur\Parts;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class Filters extends Component
{
    public string $search;
    public string $statusFilter;
    public string $selectedMonth;
    public Collection $availableMonths;
    public string $routeName;
    public array $routeParams;

    public function __construct(
        string $search = '',
        string $statusFilter = '',
        string $selectedMonth = '',
        Collection $availableMonths = new Collection(),
        string $routeName = '',
        array $routeParams = []
    ) {
        $this->search = $search;
        $this->statusFilter = $statusFilter;
        $this->selectedMonth = $selectedMonth;
        $this->availableMonths = $availableMonths;
        $this->routeName = $routeName;
        $this->routeParams = $routeParams;
    }

    public function render(): View
    {
        return view('clean.organisateur.parts.filters', [
            'search' => $this->search,
            'statusFilter' => $this->statusFilter,
            'selectedMonth' => $this->selectedMonth,
            'availableMonths' => $this->availableMonths,
            'routeName' => $this->routeName,
            'routeParams' => $this->routeParams,
        ]);
    }
}
