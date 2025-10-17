<?php

use App\Models\Formation;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Services\FormationService;


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

Route::get('/test', function (FormationService $formations) {
    $x = $formations->team()->makeFormationVisibleForTeam(Formation::find(1),Team::find(1));
    dd($x);
    return response()->json([
        'all_formations' => $formations->admin()->list()->map->only([
            'id',
            'title',
            'level',
            'money_amount',
        ]),
    ]);
});
