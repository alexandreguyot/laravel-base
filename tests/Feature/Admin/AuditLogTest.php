<?php

namespace Tests\Feature\Admin;

use App\Models\AuditLog;
use App\Models\User;
use Tests\AdminTestCase;

class AuditLogTest extends AdminTestCase
{

    // --- Access ---

    public function test_audit_logs_list_requires_auth(): void
    {
        $this->get('/audit-logs')->assertRedirect('/login');
    }

    public function test_audit_logs_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/audit-logs')
            ->assertOk();
    }

    public function test_audit_log_can_be_created_in_database(): void
    {
        $admin = $this->adminUser();

        AuditLog::create([
            'description'  => 'Utilisateur créé',
            'subject_id'   => $admin->id,
            'subject_type' => User::class,
            'user_id'      => $admin->id,
            'host'         => '127.0.0.1',
        ]);

        $this->assertDatabaseHas('audit_logs', ['description' => 'Utilisateur créé']);
    }

    public function test_audit_log_show_is_accessible(): void
    {
        $admin = $this->adminUser();
        $log   = AuditLog::create([
            'description' => 'Action test',
            'subject_id'  => $admin->id,
            'subject_type' => User::class,
            'user_id'     => $admin->id,
            'host'        => '127.0.0.1',
        ]);

        $this->actingAs($admin)
            ->get("/audit-logs/{$log->id}")
            ->assertOk();
    }

    // --- Create/destroy are restricted ---

    public function test_audit_log_create_is_not_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/audit-logs/create')
            ->assertNotFound();
    }
}
