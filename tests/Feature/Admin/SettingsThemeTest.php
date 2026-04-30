<?php

namespace Tests\Feature\Admin;

use App\Models\AppSetting;
use Tests\AdminTestCase;

class SettingsThemeTest extends AdminTestCase
{
    public function test_settings_update_validates_input(): void
    {
        $this->actingAs($this->adminUser())
            ->post('/settings', [
                'nav_layout'   => 'sideways', // invalid
                'ui_theme'     => 'light',
                'icon_library' => 'lucide',
            ])
            ->assertSessionHasErrors('nav_layout');
    }

    public function test_settings_update_persists_values(): void
    {
        $this->actingAs($this->adminUser())
            ->post('/settings', [
                'nav_layout'   => 'top',
                'ui_theme'     => 'light',
                'icon_library' => 'lucide',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('app_settings', ['key' => 'nav_layout', 'value' => 'top']);
    }

    public function test_theme_endpoint_updates_user_theme(): void
    {
        $admin = $this->adminUser();

        $this->actingAs($admin)
            ->postJson('/settings/theme', ['theme' => 'dark', 'scope' => 'user'])
            ->assertOk()
            ->assertJson(['ok' => true, 'theme' => 'dark']);

        $this->assertEquals('dark', $admin->fresh()->ui_theme);
    }

    public function test_theme_endpoint_updates_global_theme(): void
    {
        $this->actingAs($this->adminUser())
            ->postJson('/settings/theme', ['theme' => 'cupcake', 'scope' => 'global'])
            ->assertOk();

        $this->assertDatabaseHas('app_settings', ['key' => 'ui_theme', 'value' => 'cupcake']);
    }

    public function test_theme_endpoint_validates_theme_field(): void
    {
        $this->actingAs($this->adminUser())
            ->postJson('/settings/theme', ['scope' => 'user'])
            ->assertStatus(422);
    }
}
