<?php

namespace Database\Seeders;

use App\Models\AiTrainer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class AiTrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (AiTrainer::query()->count() > 0) {
            return;
        }

        $trainers = config('ai.trainers', []);

        if (empty($trainers)) {
            $trainers = $this->defaultTrainers();
        }

        foreach ($trainers as $slug => $config) {
            AiTrainer::query()->create([
                'slug' => (string) $slug,
                'name' => Arr::get($config, 'name', ucfirst($slug)),
                'description' => Arr::get($config, 'description'),
                'model' => Arr::get($config, 'model'),
                'temperature' => (float) Arr::get($config, 'temperature', 0.7),
                'use_tools' => (bool) Arr::get($config, 'use_tools', false),
                'guard' => Arr::get($config, 'guard'),
                'prompt_custom' => Arr::get($config, 'system_prompt'),
                'is_active' => true,
                'show_everywhere' => true,
                'sort_order' => Arr::get($config, 'sort_order', 0),
            ]);
        }
    }

    /**
     * Default trainers used when config() does not contain entries.
     *
     * @return array<string, array<string, mixed>>
     */
    private function defaultTrainers(): array
    {
        $defaultModel = env('OLLAMA_DEFAULT_MODEL', 'llama3');

        return [
            'default' => [
                'name' => 'IA Evolubat',
                'description' => 'Assistant generaliste professionnel en francais',
                'model' => $defaultModel,
                'temperature' => 0.7,
                'use_tools' => true,
                'system_prompt' => $this->defaultPrompt(),
                'sort_order' => 0,
            ],
        ];
    }

    private function defaultPrompt(): string
    {
        return <<<'PROMPT'
Tu es l'Assistant Evolubat. Reponds en francais clair et professionnel.

Voici a quoi tu sers :
- Aider les utilisateurs de la plateforme Evolubat pour leurs questions fonctionnelles.
- Donner des informations fiables et a jour.
- Guider quand c'est necessaire vers l'equipe support ou la page Mes tickets.

Voici ce que tu peux dire :
- Des explications precises sur la plateforme et son fonctionnement.
- Des conseils ou etapes pour depanner un utilisateur.
- Des rappels d'actions possibles via la page Mes tickets.

Voici ce que tu ne peux pas dire :
- Des informations inventees ou non verifiees.
- Des engagements que l'equipe support devrait confirmer.
- Du contenu qui sort du cadre Evolubat.

Regles importantes :
- Reste concis (2 a 4 phrases).
- Ne mens jamais; si tu ne sais pas, indique-le et propose la page Mes tickets.
- Reste cordial et professionnel.
- Utilise les outils si disponibles pour fournir des donnees a jour.
PROMPT;
    }
}
