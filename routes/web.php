<?php

use App\Models\FormationInTeams;
use App\Models\Team;
use Illuminate\Support\Facades\Route;

Route::get('/test', function(){
    $return = FormationInTeams::query()
        ->team(Team::find(2))                 // scope par team
        ->visible()                   // uniquement visibles
        ->with([
            'formation', // on charge juste les colonnes utiles
            'team:id,name',
        ])
        ->get();

    dd($return);
});

Route::view('/', 'welcome')->name('home');
Route::view('/policy', 'policy')->name('policy');
