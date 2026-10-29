<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_without_dashboard_permission_are_redirected_to_pos(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.pos.index'));
    }
}
