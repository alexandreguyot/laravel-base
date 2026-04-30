<?php

namespace Tests\Feature\Admin;

use App\Models\FeatureFlag;
use Tests\AdminTestCase;

class FeatureFlagTest extends AdminTestCase
{

    private function flag(array $attrs = []): FeatureFlag
    {
        // Use a key that exists in config/features.php (required by the toggle controller)
        return FeatureFlag::create(array_merge([
            'key'     => 'crm',
            'label'   => 'CRM',
            'enabled' => false,
            'group'   => 'Modules',
        ], $attrs));
    }

    public function test_feature_flags_list_requires_auth(): void
    {
        $this->get('/feature-flags')->assertRedirect('/login');
    }

    public function test_feature_flags_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/feature-flags')
            ->assertOk();
    }

    public function test_feature_flag_is_shown_in_list(): void
    {
        $admin = $this->adminUser();
        $this->flag();

        $this->assertDatabaseHas('feature_flags', ['key' => 'crm']);
        $this->actingAs($admin)->get('/feature-flags')->assertOk();
    }

    public function test_flag_can_be_toggled_on(): void
    {
        $admin = $this->adminUser();
        $flag  = $this->flag(['enabled' => false]);

        $this->actingAs($admin)
            ->post("/feature-flags/{$flag->key}/toggle")
            ->assertRedirect();

        $this->assertTrue($flag->fresh()->enabled);
    }

    public function test_flag_can_be_toggled_off(): void
    {
        $admin = $this->adminUser();
        $flag  = $this->flag(['enabled' => true]);

        $this->actingAs($admin)
            ->post("/feature-flags/{$flag->key}/toggle")
            ->assertRedirect();

        $this->assertFalse($flag->fresh()->enabled);
    }

    public function test_toggle_requires_auth(): void
    {
        $flag = $this->flag();

        $this->post("/feature-flags/{$flag->key}/toggle")
            ->assertRedirect('/login');
    }

    public function test_feature_flag_audit_log_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/feature-flags/audit')
            ->assertOk();
    }

    public function test_feature_service_returns_correct_state(): void
    {
        $this->flag(['key' => 'crm', 'enabled' => true]);

        $service = app(\App\Services\FeatureService::class);

        $this->assertTrue($service->isEnabled('crm'));
        $this->assertFalse($service->isDisabled('crm'));
    }

    public function test_feature_service_returns_false_for_disabled_flag(): void
    {
        $this->flag(['key' => 'lms', 'enabled' => false]);

        $service = app(\App\Services\FeatureService::class);

        $this->assertFalse($service->isEnabled('lms'));
        $this->assertTrue($service->isDisabled('lms'));
    }

    public function test_feature_service_returns_false_for_missing_flag(): void
    {
        $service = app(\App\Services\FeatureService::class);

        $this->assertFalse($service->isEnabled('nonexistent'));
    }
}
