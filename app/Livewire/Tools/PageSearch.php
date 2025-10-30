<?php

namespace App\Livewire\Tools;

use Livewire\Attributes\On;
use Livewire\Component;

class PageSearch extends Component
{
    public bool $isOpen = false;
    public string $src = '';

    public function mount(): void
    {
        $this->src = route('superadmin.overview');
    }

    #[On('page-search-toggle')]
    public function toggle(): void
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function render()
    {
        return view('livewire.tools.page-search');
    }
}

