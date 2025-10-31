<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RouteTest extends TestCase
{
    public function test_all_routes_with_test_parameter(): void
    {
        // Créer un utilisateur test simplement
        $user = User::factory()->create();

        // Pas besoin de créer toutes les données, on utilise les IDs statiques
        // qui correspondent aux paramètres de test (1, 1, 1, etc.)

        // Authentifier l'utilisateur
        $this->actingAs($user);

        $routes = collect(Route::getRoutes()->getRoutes())
            ->filter(function ($route) {
                // Filtrer uniquement les routes GET web (pas API, pas console)
                return in_array('GET', $route->methods())
                    && $route->getDomain() === null
                    && ! str_starts_with($route->uri(), 'api/')
                    && ! str_starts_with($route->uri(), 'storage/')
                    && ! str_starts_with($route->uri(), 'livewire/')
                    && ! str_starts_with($route->uri(), '_debugbar/')
                    && ! str_starts_with($route->uri(), 'sanctum/');
            });

        $this->assertGreaterThan(0, $routes->count(), 'Aucune route web GET trouvée');

        $skippedRoutes = [];
        $failedRoutes = [];
        $testedRoutes = 0;
        $totalRoutes = $routes->count();

        foreach ($routes as $route) {
            $uri = $route->uri();
            $routeName = $route->getName() ?? 'unnamed';

            // Tenter de résoudre les paramètres dynamiques avec des valeurs factices
            $resolvedUri = $this->resolveRouteParameters($uri);

            if ($resolvedUri === false) {
                $skippedRoutes[] = $uri." ({$routeName})";

                continue;
            }

            // Construire l'URL avec le paramètre test
            $testUrl = $resolvedUri.(str_contains($resolvedUri, '?') ? '&' : '?').'test=1';

            try {
                $response = $this->get($testUrl);

                // Vérifier les erreurs dans le contenu de la réponse même si le statut semble correct
                $responseContent = $response->getContent();
                $isViewError = str_contains($responseContent, 'View [') && str_contains($responseContent, '] not found');
                $isFatalError = str_contains($responseContent, 'Fatal error') || str_contains($responseContent, 'Parse error');

                if ($isViewError || $isFatalError) {
                    $failedRoutes[] = $resolvedUri." ({$routeName}) - View/Error: ".($isViewError ? 'View not found' : 'Fatal error');
                } elseif (! in_array($response->getStatusCode(), [200, 302, 403, 404])) {
                    $failedRoutes[] = $resolvedUri." ({$routeName}) - Status: {$response->getStatusCode()}";
                } else {
                    $testedRoutes++;
                }
            } catch (\Exception $e) {
                $failedRoutes[] = $resolvedUri." ({$routeName}) - Exception: ".$e->getMessage();
            }
        }

        // Rapport final du test
        $this->addToAssertionCount(1); // Compté comme un test réussi

        echo "\n=== Rapport du test des routes ===\n";
        echo "Total routes GET web: {$totalRoutes}\n";
        echo "Routes testées avec succès: {$testedRoutes}\n";
        echo 'Routes ignorées (paramètres dynamiques): '.count($skippedRoutes)."\n";
        echo 'Routes échouées: '.count($failedRoutes)."\n";

        if (count($failedRoutes) > 0) {
            echo "\nRoutes échouées:\n";
            foreach ($failedRoutes as $failed) {
                echo "- {$failed}\n";
            }
        }

        // Le test réussit si aucune route n'a échoué
        $this->assertEmpty($failedRoutes, count($failedRoutes).' route(s) ont échoué le test avec le paramètre test');
    }

    private function resolveRouteParameters(string $uri): string|false
    {
        // Mapping des paramètres vers des valeurs par défaut
        $parameterMapping = [
            '{team}' => '1',           // Premier team
            '{formation}' => '1',      // Première formation
            '{chapter}' => '1',        // Premier chapitre
            '{lesson}' => '1',         // Première lesson
            '{quiz}' => '1',           // Premier quiz
            '{question}' => '1',       // Première question
            '{choice}' => '1',         // Premier choix
            '{invitation}' => '1',     // Première invitation
            '{tutorial}' => 'policy',  // Tutorial par défaut
            '{token}' => 'test-token', // Token fictif
            '{student}' => '1',        // Premier étudiant
            '{document}' => '1',       // Premier document
            '{attempt}' => '1',        // Première tentative
        ];

        // Si pas de paramètres dynamiques, retourner l'URI telle quelle
        if (! preg_match('/\{[^}]+\}/', $uri)) {
            return $uri;
        }

        $resolvedUri = $uri;

        // Remplacer chaque paramètre trouvé
        foreach ($parameterMapping as $parameter => $value) {
            $resolvedUri = str_replace($parameter, $value, $resolvedUri);
        }

        // Vérifier s'il reste des paramètres non résolus
        if (preg_match('/\{[^}]+\}/', $resolvedUri)) {
            // Il y a encore des paramètres inconnus, ignorer cette route
            return false;
        }

        return $resolvedUri;
    }
}
