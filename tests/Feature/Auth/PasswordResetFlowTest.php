<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_form_is_accessible(): void
    {
        $this->get('/password/reset')->assertOk();
    }

    public function test_password_reset_link_is_sent(): void
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'reset@test.com']);

        $this->post('/password/email', ['email' => 'reset@test.com'])
            ->assertRedirect();

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_password_reset_requires_valid_email(): void
    {
        $this->post('/password/email', ['email' => 'not-an-email'])
            ->assertSessionHasErrors('email');
    }

    public function test_password_reset_link_for_unknown_email_does_not_error(): void
    {
        Notification::fake();

        $this->post('/password/email', ['email' => 'nobody@test.com']);

        Notification::assertNothingSent();
    }

    public function test_reset_form_is_accessible_with_token(): void
    {
        $this->get('/password/reset/some-fake-token')->assertOk();
    }
}
