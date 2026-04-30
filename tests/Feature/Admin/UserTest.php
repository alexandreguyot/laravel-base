<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\AdminTestCase;

class UserTest extends AdminTestCase
{

    // --- List ---

    public function test_user_list_requires_auth(): void
    {
        $this->get('/users')->assertRedirect('/login');
    }

    public function test_user_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/users')
            ->assertOk();
    }

    public function test_user_list_shows_users(): void
    {
        $admin = $this->adminUser();
        $other = User::factory()->create(['name' => 'Jean Dupont']);

        $this->assertDatabaseHas('users', ['name' => 'Jean Dupont']);
        $this->actingAs($admin)->get('/users')->assertOk();
    }

    // --- Show ---

    public function test_user_show_page_is_accessible(): void
    {
        $admin = $this->adminUser();
        $user  = User::factory()->create();

        $this->actingAs($admin)
            ->get("/users/{$user->id}")
            ->assertOk();
    }

    public function test_user_show_returns_404_for_unknown_user(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/users/99999')
            ->assertNotFound();
    }

    // --- Edit ---

    public function test_user_edit_page_is_accessible(): void
    {
        $admin = $this->adminUser();
        $user  = User::factory()->create();

        $this->actingAs($admin)
            ->get("/users/{$user->id}/edit")
            ->assertOk();
    }

    // --- Profile ---

    public function test_profile_page_is_accessible(): void
    {
        $user = $this->adminUser();

        $this->actingAs($user)
            ->get('/profile')
            ->assertOk();
    }

    // --- Soft deletes ---

    public function test_soft_deleted_user_does_not_appear_in_list_by_default(): void
    {
        $admin   = $this->adminUser();
        $deleted = User::factory()->create(['name' => 'Deleted Person']);
        $deleted->delete();

        $this->actingAs($admin)
            ->get('/users')
            ->assertDontSee('Deleted Person');
    }
}
