<?php

namespace Tests\Feature\Admin;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Tests\AdminTestCase;

class RolePermissionTest extends AdminTestCase
{

    // --- Roles ---

    public function test_roles_list_requires_auth(): void
    {
        $this->get('/roles')->assertRedirect('/login');
    }

    public function test_roles_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/roles')
            ->assertOk();
    }

    public function test_role_can_be_created(): void
    {
        Role::create(['title' => 'Admin']);

        $this->assertDatabaseHas('roles', ['title' => 'Admin']);
    }

    public function test_role_show_page_is_accessible(): void
    {
        $admin = $this->adminUser();
        $role  = Role::create(['title' => 'Manager']);

        $this->actingAs($admin)
            ->get("/roles/{$role->id}")
            ->assertOk();
    }

    public function test_role_edit_page_is_accessible(): void
    {
        $admin = $this->adminUser();
        $role  = Role::create(['title' => 'Editor']);

        $this->actingAs($admin)
            ->get("/roles/{$role->id}/edit")
            ->assertOk();
    }

    // --- Permissions ---

    public function test_permissions_list_requires_auth(): void
    {
        $this->get('/permissions')->assertRedirect('/login');
    }

    public function test_permissions_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/permissions')
            ->assertOk();
    }

    public function test_permission_can_be_created(): void
    {
        Permission::create(['title' => 'manage-users']);

        $this->assertDatabaseHas('permissions', ['title' => 'manage-users']);
    }

    public function test_permission_show_page_is_accessible(): void
    {
        $admin      = $this->adminUser();
        $permission = Permission::create(['title' => 'view-reports']);

        $this->actingAs($admin)
            ->get("/permissions/{$permission->id}")
            ->assertOk();
    }

    // --- Role ↔ User assignment ---

    public function test_user_can_be_assigned_a_role(): void
    {
        $user = User::factory()->create();
        $role = Role::create(['title' => 'SuperAdmin']);

        $user->roles()->attach($role);

        $this->assertTrue($user->roles->contains($role));
    }

    public function test_role_can_have_permissions(): void
    {
        $role       = Role::create(['title' => 'Editor']);
        $permission = Permission::create(['title' => 'edit-content']);

        $role->permissions()->attach($permission);

        $this->assertTrue($role->permissions->contains($permission));
    }
}
