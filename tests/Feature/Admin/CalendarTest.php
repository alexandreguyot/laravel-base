<?php

namespace Tests\Feature\Admin;

use Tests\AdminTestCase;

class CalendarTest extends AdminTestCase
{
    public function test_system_calendar_requires_auth(): void
    {
        $this->get('/system-calendars')->assertRedirect('/login');
    }

    public function test_system_calendar_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/system-calendars')
            ->assertOk();
    }

    public function test_task_calendar_requires_auth(): void
    {
        $this->get('/task-calendars')->assertRedirect('/login');
    }

    public function test_task_calendar_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/task-calendars')
            ->assertOk();
    }
}
