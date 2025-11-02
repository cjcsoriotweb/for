<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;

Artisan::command('ia:repond', function () {
    $start = microtime(true);
    $maxDuration = 50; // secondes max avant d'arrêter
    $interval = 5;    // secondes entre chaque itération

    while (true) {
        // Ta logique ici (ex: $this->call('assistants:respond-pending'))
        $this->call('assistants:respond-pending');

        // Vérifie si on dépasse le temps limite
        if ((microtime(true) - $start) >= $maxDuration) {
            break;
        }

        sleep($interval);
    }
})->purpose('Lancer la reponse automatique des assistants IA.');


