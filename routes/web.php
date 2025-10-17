<?php

use App\Models\FormationInTeams;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function(){
    
    if(Auth::user()){
        return view('welcome.welcome-back-auth');
    } else {
        return view('welcome.hello-guest');
    }
    
})->name('home');

Route::get('/presentation', function(){
    
    return view('welcome.presentation');

    
})->name('presentation');

Route::view('/policy', 'policy')->name('policy');
