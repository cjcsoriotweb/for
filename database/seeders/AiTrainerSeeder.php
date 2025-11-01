<?php

namespace Database\Seeders;

use App\Models\AiTrainer;
use Illuminate\Database\Seeder;

class AiTrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $default = config('ai.default_site_trainer', []);
        if (! is_array($default)) {
            $default = [];
        }

        $defaultSlug = $default['slug'] ?? config('ai.default_trainer_slug', 'assistant-ia-generaliste');
        $provider = $default['provider'] ?? config('ai.default_driver', 'ollama');
        $model = $default['model'] ?? config("ai.providers.$provider.default_model", 'llama3');

        $defaultSettings = isset($default['settings']) && is_array($default['settings'])
            ? $default['settings']
            : [];

        if (! array_key_exists('temperature', $defaultSettings)) {
            $defaultSettings['temperature'] = (float) config("ai.providers.$provider.temperature", 0.7);
        } else {
            $defaultSettings['temperature'] = (float) $defaultSettings['temperature'];
        }

        $trainer = AiTrainer::firstOrCreate(
            ['slug' => $defaultSlug],
            [
                'name' => $default['name'] ?? 'Assistant IA',
                'provider' => $provider,
                'model' => $model,
                'description' => $default['description'] ?? null,
                'prompt' => $default['prompt'] ?? null,
                'avatar_path' => $default['avatar_path'] ?? null,
                'is_default' => true,
                'is_active' => true,
                'settings' => $defaultSettings,
            ]
        );

        AiTrainer::firstOrCreate(
            ['slug' => 'ia-formateur-generaliste'],
            [
                'name' => 'Formateur IA (Ollama)',
                'provider' => 'ollama',
                'model' => config('ai.providers.ollama.default_model', 'llama3'),
                'description' => 'Assistant pedagogique propulse par Ollama pour des formations.',
                'prompt' => <<<'PROMPT'
Tu es un formateur virtuel experimente reposant sur un modele accessible via Ollama. Reponds avec clarte, concision et pedagogie aux apprenants des formations FOR.
Adapte ton langage au niveau de l'apprenant et relie ta reponse aux objectifs de la formation.

Regles pedagogiques :
- Explique les concepts etape par etape
- Utilise des analogies quand c'est approprie
- Pose des questions pour verifier la comprehension
- Encourage l'apprentissage pratique
PROMPT,
                'is_default' => false,
                'is_active' => true,
                'settings' => [
                    'temperature' => (float) config('ai.providers.ollama.temperature', 0.7),
                ],
            ]
        );

        $formationIds = \App\Models\Formation::query()->pluck('id');

        if ($formationIds->isNotEmpty()) {
            $trainer->formations()->syncWithoutDetaching($formationIds);
        }
    }
}
