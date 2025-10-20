<?php

namespace App\Livewire\Compose;

use Livewire\Component;

class Confirm extends Component
{
    public $confirm = false;

    public $confirmText = '';

    public function render()
    {
        return view('livewire.compose.confirm');
    }
}
