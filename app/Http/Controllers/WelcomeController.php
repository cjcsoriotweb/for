<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function home(){
        if(auth()->check()){
            return redirect()->route('welcome-back');
        }
        return view('welcome');
    }
}
