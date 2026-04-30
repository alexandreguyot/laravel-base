<?php

namespace Tests\Feature\Admin;

use App\Models\CrmCustomer;
use App\Models\CrmNote;
use App\Models\CrmStatus;
use Tests\AdminTestCase;

class CrmTest extends AdminTestCase
{

    private function crmStatus(): CrmStatus
    {
        return CrmStatus::create(['name' => 'Lead']);
    }

    private function customer(CrmStatus $status): CrmCustomer
    {
        return CrmCustomer::create([
            'first_name' => 'Jean',
            'last_name'  => 'Martin',
            'status_id'  => $status->id,
            'email'      => 'jean.martin@example.com',
        ]);
    }

    // --- Statuses ---

    public function test_crm_statuses_list_requires_auth(): void
    {
        $this->get('/crm-statuses')->assertRedirect('/login');
    }

    public function test_crm_statuses_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/crm-statuses')
            ->assertOk();
    }

    public function test_crm_status_is_shown_in_list(): void
    {
        $admin = $this->adminUser();
        CrmStatus::create(['name' => 'Prospect']);

        $this->assertDatabaseHas('crm_statuses', ['name' => 'Prospect']);
        $this->actingAs($admin)->get('/crm-statuses')->assertOk();
    }

    // --- Customers ---

    public function test_crm_customers_list_requires_auth(): void
    {
        $this->get('/crm-customers')->assertRedirect('/login');
    }

    public function test_crm_customers_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/crm-customers')
            ->assertOk();
    }

    public function test_crm_customer_is_shown_in_list(): void
    {
        $admin    = $this->adminUser();
        $status   = $this->crmStatus();
        $customer = $this->customer($status);

        $this->assertDatabaseHas('crm_customers', ['first_name' => 'Jean']);
        $this->actingAs($admin)->get('/crm-customers')->assertOk();
    }

    public function test_crm_customer_show_page_is_accessible(): void
    {
        $admin    = $this->adminUser();
        $customer = $this->customer($this->crmStatus());

        $this->actingAs($admin)
            ->get("/crm-customers/{$customer->id}")
            ->assertOk();
    }

    public function test_crm_customer_edit_page_is_accessible(): void
    {
        $admin    = $this->adminUser();
        $customer = $this->customer($this->crmStatus());

        $this->actingAs($admin)
            ->get("/crm-customers/{$customer->id}/edit")
            ->assertOk();
    }

    public function test_crm_customer_can_be_soft_deleted(): void
    {
        $customer = $this->customer($this->crmStatus());

        $customer->delete();

        $this->assertSoftDeleted('crm_customers', ['id' => $customer->id]);
    }

    // --- Notes ---

    public function test_crm_notes_list_requires_auth(): void
    {
        $this->get('/crm-notes')->assertRedirect('/login');
    }

    public function test_crm_notes_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/crm-notes')
            ->assertOk();
    }

    public function test_crm_note_can_be_created(): void
    {
        $customer = $this->customer($this->crmStatus());

        $note = CrmNote::create([
            'note'        => 'Premier contact téléphonique',
            'customer_id' => $customer->id,
        ]);

        $this->assertDatabaseHas('crm_notes', ['note' => 'Premier contact téléphonique']);
    }

    public function test_crm_note_belongs_to_customer(): void
    {
        $customer = $this->customer($this->crmStatus());

        $note = CrmNote::create([
            'note'        => 'Suivi effectué',
            'customer_id' => $customer->id,
        ]);

        $this->assertEquals($customer->id, $note->customer->id);
    }
}
