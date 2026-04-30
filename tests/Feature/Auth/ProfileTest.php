<?php

namespace Tests\Feature\Auth;

use Tests\AdminTestCase;

class ProfileTest extends AdminTestCase
{
    public function test_profile_requires_auth(): void
    {
        $this->get('/profile')->assertRedirect('/login');
    }

    public function test_profile_is_accessible_for_authorized_user(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/profile')
            ->assertOk();
    }
}
