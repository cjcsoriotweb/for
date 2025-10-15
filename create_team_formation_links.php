<?php

require_once 'vendor/autoload.php';

// Créer une équipe et lier les formations
$team = \App\Models\Team::create([
    'name' => 'Équipe Formation',
    'user_id' => 1,
    'personal_team' => false,
]);

$formations = \App\Models\Formation::all();

foreach ($formations as $formation) {
    $team->formations()->attach($formation->id, [
        'visible' => true,
        'approved_at' => now(),
        'approved_by' => 1,
    ]);
}

echo "Équipe créée et formations liées.\n";
