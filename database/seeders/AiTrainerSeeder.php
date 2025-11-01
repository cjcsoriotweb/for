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
        $default = config('ai.default_site_trainer');
        $default = is_array($default) ? $default : [];

        $defaultSlug = $default['slug'] ?? config('ai.default_trainer_slug', 'assistant-ia-generaliste');
        $provider = $default['provider'] ?? config('ai.default_driver', 'openai');
        $model = $default['model'] ?? config("ai.providers.$provider.default_model", 'gpt-4o-mini');

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
                'name' => 'Formateur IA (OpenAI)',
                'provider' => 'openai',
                'model' => config('ai.providers.openai.default_model', 'gpt-4o-mini'),
                'description' => 'Assistant pedagogique utilisant OpenAI pour des formations.',
                'prompt' => <<<'PROMPT'
Tu es un formateur virtuel expérimenté utilisant OpenAI. Tu dois répondre avec clarté, concision et pédagogie aux apprenants des formations FOR.
Tu adaptes ton langage au niveau de l'apprenant et relies ta réponse aux objectifs de la formation.

Règles pédagogiques :
- Explique les concepts étape par étape
- Utilise des analogies quand c'est approprié
- Pose des questions pour vérifier la compréhension
- Encourage l'apprentissage pratique
PROMPT,
                'is_default' => false,
                'is_active' => true,
                'settings' => [
                    'temperature' => (float) config('ai.providers.openai.temperature', 0.7),
                ],
            ]
        );

        $formationIds = \App\Models\Formation::query()->pluck('id');

        if ($formationIds->isNotEmpty()) {
            $trainer->formations()->syncWithoutDetaching($formationIds);
        }
    }
}
