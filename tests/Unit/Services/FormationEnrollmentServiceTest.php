<?php

namespace Tests\Unit\Services;

use App\Models\Formation;
use App\Models\Team;
use App\Models\User;
use App\Services\FormationEnrollmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class FormationEnrollmentServiceTest extends TestCase
{
    use RefreshDatabase;

    private FormationEnrollmentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FormationEnrollmentService::class);
    }

    public function test_can_team_afford_formation_returns_true_when_sufficient_funds()
    {
        $team = Team::create(['name' => 'Test Team', 'money' => 1000, 'personal_team' => false]);
        $formation = Formation::create([
            'team_id' => $team->id,
            'title' => 'Test Formation',
            'description' => 'Description',
            'level' => 'debutant',
            'money_amount' => 500,
        ]);

        $result = $this->service->canTeamAffordFormation($team, $formation);

        $this->assertTrue($result);
    }

    public function test_can_team_afford_formation_returns_false_when_insufficient_funds()
    {
        $team = Team::create(['name' => 'Test Team', 'money' => 300, 'personal_team' => false]);
        $formation = Formation::create([
            'team_id' => $team->id,
            'title' => 'Test Formation',
            'description' => 'Description',
            'level' => 'debutant',
            'money_amount' => 500,
        ]);

        $result = $this->service->canTeamAffordFormation($team, $formation);

        $this->assertFalse($result);
    }

    public function test_enroll_user_returns_false_when_insufficient_funds()
    {
        $team = Team::create(['name' => 'Test Team', 'money' => 300, 'personal_team' => false]);
        $formation = Formation::create([
            'team_id' => $team->id,
            'title' => 'Test Formation',
            'description' => 'Description',
            'level' => 'debutant',
            'money_amount' => 500,
        ]);
        $user = User::factory()->create();

        $result = $this->service->enrollUser($formation, $team, $user->id);

        $this->assertFalse($result);

        // Vérifier que l'utilisateur n'a pas été inscrit
        $this->assertFalse($formation->learners()->where('users.id', $user->id)->exists());

        // Vérifier que l'argent n'a pas été débité
        $team->refresh();
        $this->assertEquals(300, $team->money);
    }

    public function test_enroll_user_successfully_enrolls_and_debits_funds()
    {
        $team = Team::create(['name' => 'Test Team', 'money' => 1000, 'personal_team' => false]);
        $formation = Formation::create([
            'team_id' => $team->id,
            'title' => 'Test Formation',
            'description' => 'Description',
            'level' => 'debutant',
            'money_amount' => 500,
        ]);
        $user = User::factory()->create();

        Auth::login($user);

        $result = $this->service->enrollUser($formation, $team, $user->id);

        $this->assertTrue($result);

        // Vérifier que l'utilisateur est inscrit
        $enrollment = $formation->learners()->where('users.id', $user->id)->first();
        $this->assertNotNull($enrollment);
        $this->assertEquals($user->id, $enrollment->id);

        // Vérifier les données d'inscription
        $pivot = $enrollment->pivot;
        $this->assertEquals($team->id, $pivot->team_id);
        $this->assertEquals('in_progress', $pivot->status);
        $this->assertEquals(0, $pivot->progress_percent);

        // Vérifier que les fonds ont été débités
        $team->refresh();
        $this->assertEquals(500, $team->money);
    }
}
