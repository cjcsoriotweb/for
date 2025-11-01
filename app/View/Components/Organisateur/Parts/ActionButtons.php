<?php

namespace App\View\Components\Organisateur\Parts;

use Illuminate\View\Component;
use Illuminate\View\View;

class ActionButtons extends Component
{
    public array $buttons;

    public function __construct(array $buttons = [])
    {
        $this->buttons = $buttons;
    }

    public function render(): View
    {
        return view('in-application.organisateur.parts.action-buttons', [
            'buttons' => $this->buttons,
        ]);
    }
}
