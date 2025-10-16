<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function(){

    $return = "test";
    dd($return);
});

Route::view('/', 'welcome')->name('home');
Route::view('/policy', 'policy')->name('policy');
