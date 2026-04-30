<?php

namespace Tests\Feature\Admin;

use App\Models\CrmCustomer;
use App\Models\CrmStatus;
use Tests\AdminTestCase;

class CrmStatusWorkflowTest extends AdminTestCase
{
    public function test_customer_status_can_be_changed(): void
    {
        $prospect = CrmStatus::create(['name' => 'Prospect']);
        $client   = CrmStatus::create(['name' => 'Client']);

        $customer = CrmCustomer::create([
            'first_name' => 'Bob',
            'last_name'  => 'Smith',
            'email'      => 'bob@test.com',
            'status_id'  => $prospect->id,
        ]);

        $customer->update(['status_id' => $client->id]);

        $this->assertEquals($client->id, $customer->fresh()->status_id);
        $this->assertEquals('Client', $customer->fresh()->status->name);
    }

    public function test_two_customers_can_share_a_status(): void
    {
        $status = CrmStatus::create(['name' => 'Lead']);

        CrmCustomer::create(['first_name' => 'A', 'last_name' => 'A', 'email' => 'a@x.com', 'status_id' => $status->id]);
        CrmCustomer::create(['first_name' => 'B', 'last_name' => 'B', 'email' => 'b@x.com', 'status_id' => $status->id]);

        $this->assertEquals(2, CrmCustomer::where('status_id', $status->id)->count());
    }

    public function test_deleting_customer_does_not_delete_status(): void
    {
        $status   = CrmStatus::create(['name' => 'Active']);
        $customer = CrmCustomer::create([
            'first_name' => 'Carol',
            'last_name'  => 'Test',
            'email'      => 'c@test.com',
            'status_id'  => $status->id,
        ]);

        $customer->delete();

        $this->assertDatabaseHas('crm_statuses', ['id' => $status->id]);
        $this->assertSoftDeleted('crm_customers', ['id' => $customer->id]);
    }
}
