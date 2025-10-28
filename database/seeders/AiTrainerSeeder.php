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
        $trainer = AiTrainer::firstOrCreate(
            ['slug' => 'ia-formateur-generaliste'],
            [
                'name' => 'Formateur IA generaliste',
                'provider' => 'openai',
                'model' => 'gpt-4o-mini',
                'description' => 'Assistant pedagogique polyvalent couvrant toutes les formations.',
                'prompt' => <<<PROMPT
Tu es un formateur virtuel experimente. Tu dois repondre avec clarte, concision et pedagogie aux apprenants des formations FOR. Tu adaptes ton langage au niveau de l'apprenant et relies ta reponse aux objectifs de la formation.
PROMPT,
                'is_default' => true,
                'is_active' => true,
            ]
        );

        $formationIds = \App\Models\Formation::query()->pluck('id');

        if ($formationIds->isNotEmpty()) {
            $trainer->formations()->syncWithoutDetaching($formationIds);
        }
    }
}
