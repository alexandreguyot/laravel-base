<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'               => fake()->name(),
            'email'              => fake()->unique()->safeEmail(),
            'email_verified_at'  => now()->format('d/m/Y H:i:s'),
            'password'           => bcrypt('password'),
            'remember_token'     => Str::random(10),
            'is_approved'        => true,
            'locale'             => 'fr',
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }

    public function unapproved(): static
    {
        return $this->state(fn () => ['is_approved' => false]);
    }

    public function withTwoFactor(): static
    {
        return $this->state(fn () => [
            'two_factor_secret'       => encrypt(app('pragmarx.google2fa')->generateSecretKey()),
            'two_factor_enabled'      => true,
            'two_factor_confirmed_at' => now(),
        ]);
    }
}
