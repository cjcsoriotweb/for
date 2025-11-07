<?php

namespace App\Http\Controllers\Clean\Guest;

class PageController
{
    public function index()
    {
        return redirect()->route('user.dashboard');

        return view('out-application.guest.hello');
    }

    public function policy()
    {
        return view('out-application.guest.policy');
    }

    public function terms()
    {
        $terms = 'Vos conditions d\'utilisation ici...'; // Vous pouvez personnaliser ce contenu

        return view('out-application.guest.terms', compact('terms'));
    }
}
