<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function home(){
        if(auth()->check()){
            return redirect()->route('yoursApplications');
        }
        return view('welcome');
    }

    public function policy(){
        return view('welcome');
        
    }
}
