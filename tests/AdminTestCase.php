<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;

abstract class AdminTestCase extends TestCase
{
    use RefreshDatabase;

    /**
     * Crée un utilisateur avec accès complet (bypass de tous les gates).
     * Simule un super-admin sans avoir à créer rôles + permissions en base.
     */
    protected function adminUser(): User
    {
        $user = User::factory()->create();

        // Autorise tout pour cet utilisateur (équivalent super-admin)
        Gate::before(fn (User $u) => $u->id === $user->id ? true : null);

        return $user;
    }
}
