<?php

namespace Tests\Feature\Admin;

use App\Models\FeatureFlag;
use Tests\AdminTestCase;

class FeatureFlagSyncTest extends AdminTestCase
{
    public function test_sync_requires_auth(): void
    {
        $this->post('/feature-flags/sync')->assertRedirect('/login');
    }

    public function test_sync_creates_flags_from_config(): void
    {
        $this->assertEquals(0, FeatureFlag::count());

        $this->actingAs($this->adminUser())
            ->post('/feature-flags/sync')
            ->assertRedirect();

        $configKeys = array_keys(config('features', []));
        $this->assertGreaterThan(0, FeatureFlag::count());

        foreach ($configKeys as $key) {
            $this->assertDatabaseHas('feature_flags', ['key' => $key]);
        }
    }

    public function test_sync_is_idempotent(): void
    {
        $admin = $this->adminUser();

        $this->actingAs($admin)->post('/feature-flags/sync');
        $countAfterFirst = FeatureFlag::count();

        $this->actingAs($admin)->post('/feature-flags/sync');
        $countAfterSecond = FeatureFlag::count();

        $this->assertEquals($countAfterFirst, $countAfterSecond);
    }

    public function test_audit_index_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/feature-flags/audit')
            ->assertOk();
    }
}
