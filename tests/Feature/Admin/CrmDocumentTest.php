<?php

namespace Tests\Feature\Admin;

use App\Models\CrmCustomer;
use App\Models\CrmDocument;
use App\Models\CrmStatus;
use Tests\AdminTestCase;

class CrmDocumentTest extends AdminTestCase
{
    private function customer(): CrmCustomer
    {
        $status = CrmStatus::create(['name' => 'Prospect']);
        return CrmCustomer::create([
            'first_name' => 'Alice',
            'last_name'  => 'Doc',
            'email'      => 'doc@test.com',
            'status_id'  => $status->id,
        ]);
    }

    public function test_crm_documents_list_requires_auth(): void
    {
        $this->get('/crm-documents')->assertRedirect('/login');
    }

    public function test_crm_documents_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/crm-documents')
            ->assertOk();
    }

    public function test_crm_document_can_be_created(): void
    {
        $customer = $this->customer();
        $document = CrmDocument::create([
            'customer_id' => $customer->id,
            'name'        => 'Devis 2026',
        ]);

        $this->assertDatabaseHas('crm_documents', ['id' => $document->id, 'name' => 'Devis 2026']);
    }

    public function test_crm_document_belongs_to_customer(): void
    {
        $customer = $this->customer();
        $doc      = CrmDocument::create([
            'customer_id' => $customer->id,
            'name'        => 'Contrat',
        ]);

        $this->assertEquals($customer->id, $doc->customer->id);
    }

    public function test_crm_document_show_is_accessible(): void
    {
        $admin    = $this->adminUser();
        $customer = $this->customer();
        $doc      = CrmDocument::create(['customer_id' => $customer->id, 'name' => 'Doc']);

        $this->actingAs($admin)
            ->get("/crm-documents/{$doc->id}")
            ->assertOk();
    }

    public function test_crm_document_can_be_soft_deleted(): void
    {
        $customer = $this->customer();
        $doc      = CrmDocument::create(['customer_id' => $customer->id, 'name' => 'Tmp']);

        $doc->delete();

        $this->assertSoftDeleted('crm_documents', ['id' => $doc->id]);
    }
}
