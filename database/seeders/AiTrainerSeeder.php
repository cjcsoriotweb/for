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
        // Create Ollama trainer as default
        $trainer = AiTrainer::firstOrCreate(
            ['slug' => 'assistant-ia-generaliste'],
            [
                'name' => 'Assistant IA Generaliste',
                'provider' => 'ollama',
                'model' => 'llama3',
                'description' => 'Assistant IA polyvalent utilisant Ollama pour une utilisation locale.',
                'prompt' => <<<'PROMPT'
Tu es un assistant IA polyvalent et amical. Tu aides les utilisateurs avec diverses questions et demandes.
Tu réponds de manière claire, précise et utile. Tu t'adaptes au niveau de l'utilisateur et fournis des informations pertinentes.

Règles importantes :
- Réponds toujours en français à moins que l'utilisateur te demande explicitement d'autres langues
- Sois pédagogique et explique les concepts complexes simplement
- Si tu ne sais pas quelque chose, dis-le honnêtement
- Sois positif et encourageant
PROMPT,
                'is_default' => true,
                'is_active' => true,
            ]
        );

        // Also create OpenAI trainer as alternative
        AiTrainer::firstOrCreate(
            ['slug' => 'ia-formateur-generaliste'],
            [
                'name' => 'Formateur IA (OpenAI)',
                'provider' => 'openai',
                'model' => 'gpt-4o-mini',
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
            ]
        );

        $formationIds = \App\Models\Formation::query()->pluck('id');

        if ($formationIds->isNotEmpty()) {
            $trainer->formations()->syncWithoutDetaching($formationIds);
        }
    }
}
