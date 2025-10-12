<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Formation::create([
            'title' => 'Programmation Python Avancée',
            'description' => 'Apprenez les concepts et techniques avancés de Python pour construire des applications robustes.',
            'level' => 'avancé',
        ]);

        \App\Models\Formation::create([
            'title' => 'Introduction à la Science des Données',
            'description' => 'Découvrez les bases de la science des données et les outils essentiels pour analyser des données.',
            'level' => 'débutant',
        ]);

        \App\Models\Formation::create([
            'title' => 'Développement Web avec Laravel',
            'description' => 'Apprenez à créer des applications web robustes avec le framework Laravel.',
            'level' => 'intermédiaire',
        ]);
    }
}
