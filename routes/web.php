<?php

use App\Models\Team;
use App\Services\FormationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*

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
    $x = $formations->team()->listWithTeamFlags(Team::find(1));
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

*/
