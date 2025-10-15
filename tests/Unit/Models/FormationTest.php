<?php

namespace Tests\Unit\Models;

use App\Models\Formation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormationTest extends TestCase
{
    use RefreshDatabase;

    public function test_scope_for_team_returns_visible_formations()
    {
        // Création des équipes
        $team1 = Team::factory()->create();
        $team2 = Team::factory()->create();

        // Création des formations
        $formation1 = Formation::factory()->create([
            'title' => 'Formation 1',
            'description' => 'Description 1 for formation 1',
            'level' => 'debutant',
        ]);
        $formation2 = Formation::factory()->create([
            'title' => 'Formation 2',
            'description' => 'Description 2 for formation 2',
            'level' => 'intermediaire',
        ]);

        // L'équipe 1 peut voir la formation 1
        $team1->formations()->attach($formation1->id, ['visible' => true]);
        // L'équipe 1 ne peut pas voir la formation 2
        $team1->formations()->attach($formation2->id, ['visible' => false]);

        // L'équipe 2 peut voir la formation 2
        $team2->formations()->attach($formation2->id, ['visible' => true]);

        // Test du scope ForTeam pour l'équipe 1
        $formationsForTeam1 = Formation::forTeam($team1)->get();
        $this->assertCount(1, $formationsForTeam1);
        $this->assertEquals($formation1->id, $formationsForTeam1->first()->id);

        // Test du scope ForTeam pour l'équipe 2
        $formationsForTeam2 = Formation::forTeam($team2)->get();
        $this->assertCount(1, $formationsForTeam2);
        $this->assertEquals($formation2->id, $formationsForTeam2->first()->id);
    }

    public function test_scope_for_team_accepts_team_id_integer()
    {
        $team = Team::factory()->create();
        $formation = Formation::factory()->create([
            'title' => 'Formation',
            'description' => 'Description for formation',
            'level' => 'debutant',
        ]);

        $team->formations()->attach($formation->id, ['visible' => true]);

        $formations = Formation::forTeam($team->id)->get();

        $this->assertCount(1, $formations);
        $this->assertEquals($formation->id, $formations->first()->id);
    }

    public function test_scope_admin_with_team_link_adds_pivot_columns()
    {
        $team = Team::factory()->create();
        $formationLinked = Formation::factory()->create([
            'title' => 'Linked Formation',
            'description' => 'Description for linked formation',
            'level' => 'debutant',
        ]);
        $formationNotLinked = Formation::factory()->create([
            'title' => 'Not Linked Formation',
            'description' => 'Description for not linked formation',
            'level' => 'intermediaire',
        ]);

        // Lier une formation à l'équipe
        $team->formations()->attach($formationLinked->id, [
            'visible' => true,
        ]);

        $formations = Formation::adminWithTeamLink($team)->get();

        // Vérifier que les colonnes pivots sont ajoutées
        $linkedFormation = $formations->where('id', $formationLinked->id)->first();
        $this->assertEquals(1, $linkedFormation->is_linked);
        $this->assertEquals(1, $linkedFormation->pivot_active);  // visible

        $notLinkedFormation = $formations->where('id', $formationNotLinked->id)->first();
        $this->assertEquals(0, $notLinkedFormation->is_linked);
        $this->assertNull($notLinkedFormation->pivot_active);
    }

    public function test_learners_relationship_returns_enrolled_users()
    {
        $formation = Formation::factory()->create([
            'title' => 'Test Formation',
            'description' => 'Description for test formation',
            'level' => 'debutant',
        ]);
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Inscrire les utilisateurs
        $formation->learners()->attach($user1->id, [
            'team_id' => 1,
            'status' => 'in_progress',
            'progress_percent' => 50,
            'enrolled_at' => now(),
        ]);

        $formation->learners()->attach($user2->id, [
            'team_id' => 1,
            'status' => 'completed',
            'progress_percent' => 100,
            'enrolled_at' => now(),
        ]);

        $learners = $formation->learners;

        $this->assertCount(2, $learners);
        $this->assertEquals($user1->id, $learners->where('id', $user1->id)->first()->id);
        $this->assertEquals($user2->id, $learners->where('id', $user2->id)->first()->id);

        // Vérifier les données pivots
        $user1Pivot = $learners->where('id', $user1->id)->first()->pivot;
        $this->assertEquals('in_progress', $user1Pivot->status);
        $this->assertEquals(50, $user1Pivot->progress_percent);

        $user2Pivot = $learners->where('id', $user2->id)->first()->pivot;
        $this->assertEquals('completed', $user2Pivot->status);
        $this->assertEquals(100, $user2Pivot->progress_percent);
    }

    public function test_teams_relationship_returns_teams_with_visibility()
    {
        $formation = Formation::factory()->create([
            'title' => 'Test Formation',
            'description' => 'Description for test formation',
            'level' => 'debutant',
        ]);
        $team1 = Team::factory()->create();
        $team2 = Team::factory()->create();

        $formation->teams()->attach($team1->id, ['visible' => true]);
        $formation->teams()->attach($team2->id, ['visible' => false]);

        $teams = $formation->teams;

        $this->assertCount(2, $teams);

        // Vérifier que les pivots sont chargés
        $this->assertNotNull($teams->where('id', $team1->id)->first()->pivot);
        $this->assertNotNull($teams->where('id', $team2->id)->first()->pivot);
    }
}
