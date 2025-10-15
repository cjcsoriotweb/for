<?php

namespace Tests\Feature;

use App\Models\Formation;
use App\Models\Membership;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_formation_workflow_from_visibility_to_access()
    {
        // Création des données de test
        $admin = User::factory()->create();
        $learner = User::factory()->create();
        $team = Team::factory()->create();
        $formation = Formation::factory()->create();

        // L'admin et l'apprenant sont membres de l'équipe
        Membership::create([
            'user_id' => $admin->id,
            'team_id' => $team->id,
            'role' => 'admin',
        ]);
        Membership::create([
            'user_id' => $learner->id,
            'team_id' => $team->id,
            'role' => 'member',
        ]);

        // Étape 1: Admin rend la formation visible pour l'équipe
        $this->actingAs($admin);

        $adminResponse = $this->post(route('application.admin.formations.enable', [
            'team' => $team->id,
        ]), [
            'formation_id' => $formation->id,
        ]);

        $adminResponse->assertRedirect();
        $adminResponse->assertSessionHas('status');

        // Vérifier que la formation est visible pour l'équipe
        $this->assertTrue($formation->teams()
            ->where('teams.id', $team->id)
            ->wherePivot('visible', true)
            ->exists());

        // Étape 2: Apprenant voit la formation et peut s'inscrire
        $this->actingAs($learner);

        // Vérifier que l'apprenant peut voir la page de prévisualisation
        $previewResponse = $this->get(route('application.eleve.formations.preview', [
            'team' => $team->id,
            'formation' => $formation->id,
        ]));

        $previewResponse->assertStatus(200);
        $previewResponse->assertViewHasAll(['team', 'formation']);

        // Étape 3: Apprenant s'inscrit à la formation
        $enrollResponse = $this->post(route('application.eleve.formations.enable', [
            'team' => $team->id,
            'formation' => $formation->id,
        ]), [
            'formation' => $formation->id,
        ]);

        $enrollResponse->assertRedirect(route('application.eleve.formations.continue', [
            $team->id,
            $formation->id,
        ]));
        $enrollResponse->assertSessionHas('success');

        // Vérifier que l'apprenant est inscrit
        $this->assertTrue($formation->learners()->where('users.id', $learner->id)->exists());

        $enrollment = $formation->learners()->where('users.id', $learner->id)->first()->pivot;
        $this->assertEquals($team->id, $enrollment->team_id);
        $this->assertEquals('in_progress', $enrollment->status);
        $this->assertEquals(0, $enrollment->progress_percent);

        // Étape 4: Apprenant peut accéder à l'interface de formation
        $showResponse = $this->get(route('application.eleve.formations.show', [
            'team' => $team->id,
            'formation' => $formation->id,
        ]));

        $showResponse->assertStatus(200);
        $showResponse->assertViewIs('application.eleve.formationShow');

        // Étape 5: Apprenant peut continuer sa formation
        $continueResponse = $this->get(route('application.eleve.formations.continue', [
            'team' => $team->id,
            'formation' => $formation->id,
        ]));

        $continueResponse->assertStatus(200);
        $continueResponse->assertViewIs('application.eleve.formationContinue');
    }

    public function test_unauthorized_user_cannot_access_formation_workflow()
    {
        $authorizedUser = User::factory()->create();
        $unauthorizedUser = User::factory()->create();
        $team = Team::factory()->create();
        $formation = Formation::factory()->create();

        // Seuls les utilisateurs autorisés sont membres de l'équipe
        Membership::create([
            'user_id' => $authorizedUser->id,
            'team_id' => $team->id,
            'role' => 'member',
        ]);

        $team->formations()->attach($formation->id, ['visible' => true]);

        // Essayer d'accéder avec un utilisateur non autorisé
        $this->actingAs($unauthorizedUser);

        $response = $this->post(route('application.eleve.formations.enable', [
            'team' => $team->id,
            'formation' => $formation->id,
        ]), [
            'formation' => $formation->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_formation_visibility_control_prevents_unauthorized_enrollment()
    {
        $admin = User::factory()->create();
        $learner = User::factory()->create();
        $team1 = Team::factory()->create();
        $team2 = Team::factory()->create();
        $formation = Formation::factory()->create();

        // Admin de team1, learner de team2
        Membership::create([
            'user_id' => $admin->id,
            'team_id' => $team1->id,
            'role' => 'admin',
        ]);
        Membership::create([
            'user_id' => $learner->id,
            'team_id' => $team2->id,
            'role' => 'member',
        ]);

        // Admin rend la formation visible uniquement pour team1
        $this->actingAs($admin);

        $this->post(route('application.admin.formations.enable', [
            'team' => $team1->id,
        ]), [
            'formation_id' => $formation->id,
        ]);

        // Vérifier que team1 peut voir la formation
        $this->assertTrue($formation->teams()
            ->where('teams.id', $team1->id)
            ->wherePivot('visible', true)
            ->exists());

        // Apprenant de team2 tente de s'inscrire (devrait échouer)
        $this->actingAs($learner);

        $response = $this->post(route('application.eleve.formations.enable', [
            'team' => $team2->id,
            'formation' => $formation->id,
        ]), [
            'formation' => $formation->id,
        ]);

        // Doit échouer à cause de la validation dans EnableFormationRequest
        $response->assertSessionHasErrors(['formation']);
    }

    public function test_admin_can_toggle_formation_visibility()
    {
        $admin = User::factory()->create();
        $team = Team::factory()->create();
        $formation = Formation::factory()->create();

        Membership::create([
            'user_id' => $admin->id,
            'team_id' => $team->id,
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        // Rendre visible
        $this->post(route('application.admin.formations.enable', [
            'team' => $team->id,
        ]), [
            'formation_id' => $formation->id,
        ]);

        $this->assertTrue($formation->teams()
            ->where('teams.id', $team->id)
            ->wherePivot('visible', true)
            ->exists());

        // Rendre invisible
        $this->post(route('application.admin.formations.disable', [
            'team' => $team->id,
        ]), [
            'formation_id' => $formation->id,
        ]);

        $this->assertFalse($formation->teams()
            ->where('teams.id', $team->id)
            ->wherePivot('visible', true)
            ->exists());
    }
}
