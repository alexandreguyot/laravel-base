<?php

namespace Tests\Feature\Admin;

use App\Models\UserAlert;
use Tests\AdminTestCase;

class UserAlertTest extends AdminTestCase
{
    public function test_user_alerts_list_requires_auth(): void
    {
        $this->get('/user-alerts')->assertRedirect('/login');
    }

    public function test_user_alerts_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/user-alerts')
            ->assertOk();
    }

    public function test_user_alert_can_be_created(): void
    {
        UserAlert::create([
            'message' => 'Maintenance prévue ce soir',
            'link'    => 'https://example.com/info',
        ]);

        $this->assertDatabaseHas('user_alerts', ['message' => 'Maintenance prévue ce soir']);
    }

    public function test_user_alert_show_is_accessible(): void
    {
        $admin = $this->adminUser();
        $alert = UserAlert::create(['message' => 'Info', 'link' => null]);

        $this->actingAs($admin)
            ->get("/user-alerts/{$alert->id}")
            ->assertOk();
    }

    public function test_user_alert_seen_endpoint_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/user-alerts/seen')
            ->assertOk();
    }
}
