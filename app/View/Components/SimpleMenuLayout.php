<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class SimpleMenuLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function __construct()
    {

    }
    public function render(): View
    {
        return view('layouts.simple-menu');
    }
}
