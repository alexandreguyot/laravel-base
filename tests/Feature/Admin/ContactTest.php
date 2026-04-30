<?php

namespace Tests\Feature\Admin;

use App\Models\ContactCompany;
use App\Models\ContactContact;
use App\Models\User;
use Tests\AdminTestCase;

class ContactTest extends AdminTestCase
{
    private function company(): ContactCompany
    {
        return ContactCompany::create([
            'company_name'  => 'Acme Corp',
            'company_email' => 'contact@acme.com',
        ]);
    }

    // --- Companies ---

    public function test_contact_companies_list_requires_auth(): void
    {
        $this->get('/contact-companies')->assertRedirect('/login');
    }

    public function test_contact_companies_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/contact-companies')
            ->assertOk();
    }

    public function test_contact_company_can_be_created(): void
    {
        $this->company();

        $this->assertDatabaseHas('contact_companies', ['company_name' => 'Acme Corp']);
    }

    public function test_contact_company_show_is_accessible(): void
    {
        $admin   = $this->adminUser();
        $company = $this->company();

        $this->actingAs($admin)
            ->get("/contact-companies/{$company->id}")
            ->assertOk();
    }

    public function test_contact_company_edit_is_accessible(): void
    {
        $admin   = $this->adminUser();
        $company = $this->company();

        $this->actingAs($admin)
            ->get("/contact-companies/{$company->id}/edit")
            ->assertOk();
    }

    public function test_contact_company_can_be_soft_deleted(): void
    {
        $company = $this->company();
        $company->delete();

        $this->assertSoftDeleted('contact_companies', ['id' => $company->id]);
    }

    // --- Contacts ---

    public function test_contact_contacts_list_requires_auth(): void
    {
        $this->get('/contact-contacts')->assertRedirect('/login');
    }

    public function test_contact_contacts_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/contact-contacts')
            ->assertOk();
    }

    public function test_contact_contact_can_be_created(): void
    {
        $company = $this->company();

        ContactContact::create([
            'contact_first_name' => 'Alice',
            'contact_last_name'  => 'Dupont',
            'contact_email'      => 'alice@acme.com',
            'company_id'         => $company->id,
        ]);

        $this->assertDatabaseHas('contact_contacts', ['contact_email' => 'alice@acme.com']);
    }

    public function test_contact_belongs_to_company(): void
    {
        $company = $this->company();
        $contact = ContactContact::create([
            'contact_first_name' => 'Bob',
            'contact_last_name'  => 'Smith',
            'contact_email'      => 'bob@acme.com',
            'company_id'         => $company->id,
        ]);

        $this->assertEquals($company->id, $contact->company->id);
    }
}
