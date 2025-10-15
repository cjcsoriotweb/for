<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RoutesSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_and_policy_routes_render_views(): void
    {
        $this->get(route('home'))->assertOk()->assertViewIs('welcome');
        $this->get(route('policy'))->assertOk()->assertViewIs('policy');
    }

    public function test_account_vous_index_renders_view_with_authenticated_user(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get(route('vous.index'))->assertOk()->assertViewIs('auth.vous.index');
    }

    public function test_superadmin_routes_require_superadmin_and_render_views(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['superadmin' => true]);
        $this->actingAs($user);

        $this->get(route('superadmin.home'))->assertOk()->assertViewIs('superadmin.index');
        $this->get(route('superadmin.team.create'))->assertOk()->assertViewIs('superadmin.create-team');
    }

    public function test_superadmin_routes_forbidden_for_regular_user(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['superadmin' => false]);
        $this->actingAs($user);

        $this->get(route('superadmin.home'))->assertStatus(403);
        $this->get(route('superadmin.team.create'))->assertStatus(403);
    }

    public function test_api_user_requires_auth_and_returns_user_json(): void
    {
        $this->getJson('/api/user')->assertStatus(401);

        /** @var User $user */
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->getJson('/api/user')->assertOk()->assertJsonFragment(['id' => $user->id]);
    }
}
