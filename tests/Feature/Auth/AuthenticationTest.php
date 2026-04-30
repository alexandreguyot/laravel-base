<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    // --- Login ---

    public function test_login_page_is_accessible(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ])->assertRedirect('/dashboard');
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');
    }

    public function test_user_cannot_login_with_nonexistent_email(): void
    {
        $this->post('/login', [
            'email'    => 'nobody@example.com',
            'password' => 'password',
        ])->assertSessionHasErrors('email');
    }

    // --- Logout ---

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }

    // --- Redirect guests ---

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    // --- Email verification ---

    public function test_unverified_user_is_redirected_to_verification_notice(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect('/email/verify');
    }

    // --- Approval ---

    public function test_unapproved_user_is_redirected_to_approval_notice(): void
    {
        $user = User::factory()->unapproved()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect('/email/approval');
    }

    // --- Registration ---

    public function test_registration_page_is_accessible(): void
    {
        $this->get('/register')->assertOk();
    }

    public function test_user_can_register(): void
    {
        $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_registration_fails_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $this->post('/register', [
            'name'                  => 'Another User',
            'email'                 => 'existing@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])->assertSessionHasErrors('email');
    }

    public function test_registration_fails_with_mismatched_passwords(): void
    {
        $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Different1!',
        ])->assertSessionHasErrors('password');
    }

    // --- Password reset ---

    public function test_forgot_password_page_is_accessible(): void
    {
        $this->get('/password/reset')->assertOk();
    }

    public function test_password_reset_email_can_be_requested(): void
    {
        $user = User::factory()->create();

        $this->post('/password/email', ['email' => $user->email])
            ->assertSessionHas('status');
    }
}
