<?php

namespace App\Http\Controllers\Clean\Guest;

class PageController
{
    public function index()
    {
        return view('clean.guest.hello');
    }
}
