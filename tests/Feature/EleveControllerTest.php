<?php

namespace Tests\Feature;

use App\Models\Formation;
use App\Models\Membership;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Factories\FormationFactory;

class EleveControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Team $team;
    private Formation $formation;

    protected function setUp(): void
    {
        parent::setUp();

        // Création d'un utilisateur et d'une équipe
        /** @var User $user */
        $user = User::factory()->create();
        $user->markEmailAsVerified();
        $this->user = $user;
        $this->team = Team::factory()->create();
        $this->formation = Formation::factory()->create();

        // L'utilisateur est membre de l'équipe
        Membership::create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'role' => 'eleve',
        ]);

        // La formation est liée et visible pour l'équipe
        $this->team->formations()->attach($this->formation->id, ['visible' => true]);
    }

    public function test_formation_show_returns_view_when_formation_is_visible()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('application.eleve.formations.show', [
            'team' => $this->team->id,
            'formation' => $this->formation->id,
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('application.eleve.formations.show');
        $response->assertViewHasAll(['team', 'formation']);
    }

    public function test_formation_show_aborts_403_when_formation_not_visible()
    {
        // Rendre la formation invisible
        $this->team->formations()->sync([$this->formation->id => ['visible' => false]]);

        $this->actingAs($this->user);

        $response = $this->get(route('application.eleve.formations.show', [
            'team' => $this->team->id,
            'formation' => $this->formation->id,
        ]));

        $response->assertStatus(403);
    }

    public function test_formation_enable_enrolls_user_when_not_enrolled()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('application.eleve.formations.enable', [
            'team' => $this->team->id,
            'formation' => $this->formation->id,
        ]), [
            'formation' => $this->formation->id,
        ]);

        $response->assertRedirect(route('application.eleve.formations.continue', [
            $this->team->id,
            $this->formation->id,
        ]));
        $response->assertSessionHas('success');

        // Vérifier que l'utilisateur est inscrit
        $this->assertTrue($this->formation->learners()->where('users.id', $this->user->id)->exists());
    }

    // Test supprimé car la route GET formations.activer n'est qu'une redirection
    // et nécessite les mêmes autorisations que le groupe (can:eleve,team)
    // Ce qui rend le test redondant et non utile

    public function test_formation_enable_redirects_with_info_when_already_enrolled()
    {
        // Inscrire l'utilisateur d'abord
        $this->formation->learners()->attach($this->user->id, [
            'team_id' => $this->team->id,
            'status' => 'in_progress',
            'enrolled_at' => now(),
            'last_seen_at' => now(),
            'progress_percent' => 0,
        ]);

        $this->actingAs($this->user);

        $response = $this->post(route('application.eleve.formations.enable', [
            'team' => $this->team->id,
            'formation' => $this->formation->id,
        ]), [
            'formation' => $this->formation->id,
        ]);

        $response->assertRedirect(route('application.eleve.formations.continue', [
            $this->team->id,
            $this->formation->id,
        ]));
        $response->assertSessionHas('info', 'Vous êtes déjà inscrit à cette formation.');
    }

    public function test_formation_enable_validates_team_membership()
    {
        /** @var User $otherUser */
        $otherUser = User::factory()->create();
        $otherUser->markEmailAsVerified();
        $this->actingAs($otherUser);

        $response = $this->post(route('application.eleve.formations.enable', [
            'team' => $this->team->id,
            'formation' => $this->formation->id,
        ]), [
            'formation' => $this->formation->id,
        ]);

        $response->assertStatus(403); // Doit échouer car l'utilisateur n'est pas membre de l'équipe
    }

    public function test_formation_enable_validates_formation_visibility()
    {
        // Créer une formation non visible
        $invisibleFormation = Formation::factory()->create();

        $this->actingAs($this->user);

        $response = $this->post(route('application.eleve.formations.enable', [
            'team' => $this->team->id,
            'formation' => $invisibleFormation->id, // Formation non visible
        ]));

        $response->assertSessionHasErrors(['formation']);
    }

    public function test_formation_preview_returns_view()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('application.eleve.formations.preview', [
            'team' => $this->team->id,
            'formation' => $this->formation->id,
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('application.eleve.formations.preview');
        $response->assertViewHasAll(['team', 'formation']);
    }

    public function test_formation_continue_returns_view()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('application.eleve.formations.continue', [
            'team' => $this->team->id,
            'formation' => $this->formation->id,
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('application.eleve.formations.continue');
        $response->assertViewHasAll(['team', 'formation']);
    }

    public function test_formation_index_returns_view()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('application.eleve.formations.list', [
            'team' => $this->team->id,
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('application.eleve.formations.index');
        $response->assertViewHas('team');
    }

    public function test_index_returns_view()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('application.eleve.index', [
            'team' => $this->team->id,
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('application.eleve.dashboard');
        $response->assertViewHas('team');
    }
}
