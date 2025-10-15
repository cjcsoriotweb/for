<?php

namespace Tests\Unit\Services;

use App\Models\Formation;
use App\Models\Team;
use App\Services\FormationVisibilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormationVisibilityServiceTest extends TestCase
{
    use RefreshDatabase;

    private FormationVisibilityService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FormationVisibilityService::class);
    }

    public function test_is_formation_visible_for_team_returns_true_when_visible()
    {
        $formation = Formation::factory()->create();
        $team = Team::factory()->create();

        $team->formations()->attach($formation->id, ['visible' => true]);

        $result = $this->service->isFormationVisibleForTeam($formation, $team);

        $this->assertTrue($result);
    }

    public function test_is_formation_visible_for_team_returns_false_when_not_visible()
    {
        $formation = Formation::factory()->create();
        $team = Team::factory()->create();

        $team->formations()->attach($formation->id, ['visible' => false]);

        $result = $this->service->isFormationVisibleForTeam($formation, $team);

        $this->assertFalse($result);
    }

    public function test_is_formation_visible_for_team_returns_false_when_not_linked()
    {
        $formation = Formation::factory()->create();
        $team = Team::factory()->create();

        // Pas de lien entre formation et équipe

        $result = $this->service->isFormationVisibleForTeam($formation, $team);

        $this->assertFalse($result);
    }

    public function test_make_formation_visible_for_team_attaches_with_visible_true()
    {
        $formation = Formation::factory()->create();
        $team = Team::factory()->create();

        $this->service->makeFormationVisibleForTeam($formation, $team);

        $pivot = $team->formations()->where('formation_id', $formation->id)->first()?->pivot;

        $this->assertNotNull($pivot);
        $this->assertTrue($pivot->visible);
    }

    public function test_make_formation_invisible_for_team_updates_to_visible_false()
    {
        $formation = Formation::factory()->create();
        $team = Team::factory()->create();

        // D'abord rendre visible
        $this->service->makeFormationVisibleForTeam($formation, $team);

        // Ensuite rendre invisible
        $this->service->makeFormationInvisibleForTeam($formation, $team);

        $pivot = $team->formations()->where('formation_id', $formation->id)->first()?->pivot;

        $this->assertNotNull($pivot);
        $this->assertFalse($pivot->visible);
    }

    public function test_get_visible_formations_for_team_returns_only_visible_formations()
    {
        $team = Team::factory()->create();
        $formationVisible = Formation::factory()->create();
        $formationInvisible = Formation::factory()->create();

        $team->formations()->attach($formationVisible->id, ['visible' => true]);
        $team->formations()->attach($formationInvisible->id, ['visible' => false]);

        $visibleFormations = $this->service->getVisibleFormationsForTeam($team);

        $this->assertCount(1, $visibleFormations);
        $this->assertEquals($formationVisible->id, $visibleFormations->first()->id);
        $this->assertNotContains($formationInvisible->id, $visibleFormations->pluck('id'));
    }
}
