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

    public function test_is_user_enrolled_returns_true_when_user_is_enrolled()
    {
        $formation = Formation::factory()->create();
        $user = User::factory()->create();

        $formation->learners()->attach($user->id, [
            'team_id' => 1,
            'status' => 'in_progress',
            'enrolled_at' => now(),
        ]);

        Auth::login($user);

        $result = $this->service->isUserEnrolled($formation);

        $this->assertTrue($result);
    }

    public function test_is_user_enrolled_returns_false_when_user_is_not_enrolled()
    {
        $formation = Formation::factory()->create();
        $user = User::factory()->create();

        Auth::login($user);

        $result = $this->service->isUserEnrolled($formation);

        $this->assertFalse($result);
    }

    public function test_is_user_enrolled_works_with_specific_user_id()
    {
        $formation = Formation::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $formation->learners()->attach($user1->id, [
            'team_id' => 1,
            'status' => 'in_progress',
            'enrolled_at' => now(),
        ]);

        $result1 = $this->service->isUserEnrolled($formation, $user1->id);
        $result2 = $this->service->isUserEnrolled($formation, $user2->id);

        $this->assertTrue($result1);
        $this->assertFalse($result2);
    }

    public function test_enroll_user_attaches_user_with_correct_data()
    {
        $formation = Formation::factory()->create();
        $team = Team::factory()->create();
        $user = User::factory()->create();

        $this->service->enrollUser($formation, $team, $user->id);

        $enrollment = $formation->learners()->where('users.id', $user->id)->first();

        $this->assertNotNull($enrollment);
        $this->assertEquals($user->id, $enrollment->id);

        $pivot = $enrollment->pivot;
        $this->assertEquals($team->id, $pivot->team_id);
        $this->assertEquals('in_progress', $pivot->status);
        $this->assertEquals(0, $pivot->progress_percent);
        $this->assertNull($pivot->current_lesson_id);
        $this->assertNotNull($pivot->enrolled_at);
        $this->assertNotNull($pivot->last_seen_at);
    }

    public function test_enroll_user_uses_authenticated_user_by_default()
    {
        $formation = Formation::factory()->create();
        $team = Team::factory()->create();
        $user = User::factory()->create();

        Auth::login($user);

        $this->service->enrollUser($formation, $team);

        $enrollment = $formation->learners()->where('users.id', $user->id)->first();

        $this->assertNotNull($enrollment);
        $this->assertEquals($user->id, $enrollment->id);
    }

    public function test_get_user_enrollment_returns_pivot_data_when_enrolled()
    {
        $formation = Formation::factory()->create();
        $user = User::factory()->create();

        $formation->learners()->attach($user->id, [
            'team_id' => 1,
            'status' => 'completed',
            'progress_percent' => 80,
            'enrolled_at' => now(),
        ]);

        $enrollment = $this->service->getUserEnrollment($formation, $user->id);

        $this->assertNotNull($enrollment);
        $this->assertEquals('completed', $enrollment->status);
        $this->assertEquals(80, $enrollment->progress_percent);
    }

    public function test_get_user_enrollment_returns_null_when_not_enrolled()
    {
        $formation = Formation::factory()->create();
        $user = User::factory()->create();

        $enrollment = $this->service->getUserEnrollment($formation, $user->id);

        $this->assertNull($enrollment);
    }

    public function test_get_user_enrollment_uses_authenticated_user_by_default()
    {
        $formation = Formation::factory()->create();
        $user = User::factory()->create();

        $formation->learners()->attach($user->id, [
            'team_id' => 1,
            'status' => 'in_progress',
            'enrolled_at' => now(),
        ]);

        Auth::login($user);

        $enrollment = $this->service->getUserEnrollment($formation);

        $this->assertNotNull($enrollment);
        $this->assertEquals('in_progress', $enrollment->status);
    }
}
