<?php

namespace App\Http\Controllers\Clean\Tutorial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TutorialController extends Controller
{
    /**
     * Liste des tutoriels disponibles
     */
    public function index(Request $request)
    {
        // Liste des tutoriels prédéfinis
        $tutorials = [
            [
                'id' => 'introduction',
                'title' => 'Introduction à la plateforme',
                'description' => 'Découvrez les bases de notre plateforme de formation',
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', // Placeholder YouTube embed
                'duration' => '5 min',
                'category' => 'Débutant'
            ],
            [
                'id' => 'formations',
                'title' => 'Créer et gérer vos formations',
                'description' => 'Apprenez à créer des formations et à les organiser',
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'duration' => '15 min',
                'category' => 'Formateur'
            ],
            [
                'id' => 'eleves',
                'title' => 'Gestion des élèves',
                'description' => 'Suivez la progression de vos élèves et leurs résultats',
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'duration' => '10 min',
                'category' => 'Formateur'
            ],
            [
                'id' => 'equipe',
                'title' => 'Travailler en équipe',
                'description' => 'Collaboration et partage dans vos équipes',
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'duration' => '8 min',
                'category' => 'Avancé'
            ],
        ];

        // Filtrage par catégorie si demandé
        if ($request->has('category') && $request->category !== 'all') {
            $tutorials = array_filter($tutorials, function ($tutorial) use ($request) {
                return $tutorial['category'] === $request->category;
            });
        }

        // Catégories disponibles pour le filtre
        $categories = ['all' => 'Toutes les catégories'] + array_unique(array_column($tutorials, 'category'));

        return view('in-application.user.tutorials.index', compact('tutorials', 'categories'));
    }

    /**
     * Afficher un tutoriel spécifique
     */
    public function show($tutorialId)
    {
        // Tutoriels prédéfinis (en production, cela viendrait de la base de données)
        $tutorials = [
            'introduction' => [
                'id' => 'introduction',
                'title' => 'Introduction à la plateforme',
                'description' => 'Découvrez les bases de notre plateforme de formation',
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'duration' => '5 min',
                'category' => 'Débutant'
            ],
            'formations' => [
                'id' => 'formations',
                'title' => 'Créer et gérer vos formations',
                'description' => 'Apprenez à créer des formations et à les organiser',
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'duration' => '15 min',
                'category' => 'Formateur'
            ],
            'eleves' => [
                'id' => 'eleves',
                'title' => 'Gestion des élèves',
                'description' => 'Suivez la progression de vos élèves et leurs résultats',
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'duration' => '10 min',
                'category' => 'Formateur'
            ],
            'equipe' => [
                'id' => 'equipe',
                'title' => 'Travailler en équipe',
                'description' => 'Collaboration et partage dans vos équipes',
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'duration' => '8 min',
                'category' => 'Avancé'
            ],
        ];

        if (!isset($tutorials[$tutorialId])) {
            abort(404, 'Tutoriel non trouvé');
        }

        $tutorial = $tutorials[$tutorialId];

        // Tutoriels suggérés (sauf le tutoriel actuel)
        $suggested = array_filter($tutorials, function ($t) use ($tutorialId) {
            return $t['id'] !== $tutorialId;
        });

        return view('in-application.user.tutorials.show', compact('tutorial', 'suggested'));
    }
}
