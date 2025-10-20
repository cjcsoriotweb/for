<?php

namespace App\Http\Controllers\Clean\Guest;

class PageController
{
    public function index()
    {
        return view('clean.guest.hello');
    }

    public function policy()
    {
        return view('clean.guest.policy');
    }
}
