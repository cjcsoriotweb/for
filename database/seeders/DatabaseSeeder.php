<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->withPersonalTeam()->create();

        User::factory()->withPersonalTeam()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Créer des formations si elles n'existent pas
        if (\App\Models\Formation::count() == 0) {
            $this->call(FormationSeeder::class);
        }

        $this->call(AiTrainerSeeder::class);

        // Lier les formations à l'équipe créée
        $user = User::where('email', 'test@example.com')->first();
        $team = $user->currentTeam;

        if ($team && $team->formations()->count() == 0) {
            $formations = \App\Models\Formation::all();
            foreach ($formations as $formation) {
                $team->formations()->attach($formation->id, [
                    'visible' => true,
                    'approved_at' => now(),
                    'approved_by' => $user->id,
                ]);
            }
        }
    }
}
