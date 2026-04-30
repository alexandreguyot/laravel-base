<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorTest extends TestCase
{
    use RefreshDatabase;

    // --- Setup page ---

    public function test_2fa_setup_page_requires_auth(): void
    {
        $this->get('/2fa/setup')->assertRedirect('/login');
    }

    public function test_2fa_setup_page_is_accessible_when_authenticated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/2fa/setup')
            ->assertOk()
            ->assertSee('Authentification');
    }

    // --- Enable ---

    public function test_2fa_cannot_be_enabled_with_invalid_code(): void
    {
        $user = User::factory()->withTwoFactor()->create();

        $this->actingAs($user)
            ->post('/2fa/enable', ['code' => '000000'])
            ->assertSessionHasErrors('code');
    }

    public function test_2fa_enable_requires_auth(): void
    {
        $this->post('/2fa/enable', ['code' => '123456'])->assertRedirect('/login');
    }

    // --- Disable ---

    public function test_2fa_can_be_disabled(): void
    {
        $user = User::factory()->withTwoFactor()->create();

        $this->actingAs($user)
            ->post('/2fa/disable')
            ->assertRedirect();

        $user->refresh();
        $this->assertFalse($user->two_factor_enabled);
        $this->assertNull($user->two_factor_secret);
    }

    public function test_2fa_disable_requires_auth(): void
    {
        $this->post('/2fa/disable')->assertRedirect('/login');
    }

    // --- Challenge ---

    public function test_2fa_challenge_page_requires_auth(): void
    {
        $this->get('/2fa/challenge')->assertRedirect('/login');
    }

    public function test_2fa_challenge_page_is_shown_to_authenticated_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/2fa/challenge')
            ->assertOk();
    }

    // --- Verify ---

    public function test_2fa_verify_fails_with_invalid_code(): void
    {
        $user = User::factory()->withTwoFactor()->create();

        $this->actingAs($user)
            ->post('/2fa/verify', ['code' => '000000'])
            ->assertSessionHasErrors('code');
    }

    public function test_2fa_verify_requires_auth(): void
    {
        $this->post('/2fa/verify', ['code' => '123456'])->assertRedirect('/login');
    }

    // --- Middleware protection ---

    public function test_2fa_middleware_redirects_if_not_verified(): void
    {
        $user = User::factory()->withTwoFactor()->create();

        // Not verified in session → should redirect to challenge
        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect('/2fa/challenge');
    }

    public function test_2fa_middleware_passes_if_session_verified(): void
    {
        $user = User::factory()->withTwoFactor()->create();

        $this->actingAs($user)
            ->withSession(['2fa_verified' => true])
            ->get('/dashboard')
            ->assertOk();
    }
}
