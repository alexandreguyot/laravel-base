<?php

namespace Tests\Feature\Admin;

use App\Models\AppSetting;
use Tests\AdminTestCase;

class SettingsTest extends AdminTestCase
{

    // --- Access ---

    public function test_settings_page_requires_auth(): void
    {
        $this->get('/settings')->assertRedirect('/login');
    }

    public function test_settings_page_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/settings')
            ->assertOk();
    }

    // --- Update ---

    public function test_settings_can_be_updated(): void
    {
        $admin = $this->adminUser();

        $this->actingAs($admin)
            ->post('/settings', [
                'site_name'  => 'Mon Site',
                'site_email' => 'admin@monsite.com',
            ])
            ->assertRedirect();
    }

    // --- AppSetting model ---

    public function test_setting_can_be_stored_as_string(): void
    {
        AppSetting::create(['key' => 'site_name', 'value' => 'Laravel Base', 'type' => 'string']);

        $this->assertDatabaseHas('app_settings', ['key' => 'site_name', 'value' => 'Laravel Base']);
    }

    public function test_setting_typed_value_casts_boolean(): void
    {
        $setting = AppSetting::create(['key' => 'maintenance', 'value' => '1', 'type' => 'boolean']);

        $this->assertTrue($setting->typed_value);
    }

    public function test_setting_typed_value_casts_json(): void
    {
        $setting = AppSetting::create([
            'key'   => 'config',
            'value' => json_encode(['theme' => 'dark']),
            'type'  => 'json',
        ]);

        $this->assertEquals(['theme' => 'dark'], $setting->typed_value);
    }
}
