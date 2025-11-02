<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;

Artisan::command('ia:repond', function () {
    $this->call('assistants:respond-pending');
})->purpose('Lancer la reponse automatique des assistants IA.');


Schedule::command('ia:repond')
    ->everyFiveMinutes() // ou ->hourly(), ->dailyAt('02:00'), etc.
    ->withoutOverlapping()
    ->sendOutputTo(storage_path('logs/ia-repond.log'));