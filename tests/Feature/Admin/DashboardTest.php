<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\AdminTestCase;

class DashboardTest extends AdminTestCase
{

    public function test_dashboard_requires_auth(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_dashboard_requires_verified_email(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect('/email/verify');
    }

    public function test_dashboard_requires_approval(): void
    {
        $user = User::factory()->unapproved()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect('/email/approval');
    }

    public function test_dashboard_is_accessible_for_approved_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk();
    }

    public function test_dashboard_shows_expected_sections(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Tableau de bord');
    }
}
