<?php

namespace App\Http\Controllers\Clean\Formateur;

use App\Http\Controllers\Controller;

class FormateurPageController extends Controller
{
    public function home()
    {
        return view('out-application.formateur.formateur-home-page');
    }
}
