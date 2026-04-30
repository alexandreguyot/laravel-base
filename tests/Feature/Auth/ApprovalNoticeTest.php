<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalNoticeTest extends TestCase
{
    use RefreshDatabase;

    public function test_approval_notice_requires_auth(): void
    {
        $this->get('/email/approval')->assertRedirect('/login');
    }

    public function test_unapproved_user_sees_approval_notice(): void
    {
        $user = User::factory()->unapproved()->create();

        $this->actingAs($user)
            ->get('/email/approval')
            ->assertOk();
    }

    public function test_approved_user_is_redirected_away_from_approval_notice(): void
    {
        $user = User::factory()->create(); // is_approved=true

        $this->actingAs($user)
            ->get('/email/approval')
            ->assertRedirect();
    }
}
