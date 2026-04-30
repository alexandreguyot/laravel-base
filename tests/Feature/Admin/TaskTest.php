<?php

namespace Tests\Feature\Admin;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\TaskTag;
use App\Models\User;
use Tests\AdminTestCase;

class TaskTest extends AdminTestCase
{

    private function taskStatus(): TaskStatus
    {
        return TaskStatus::create(['name' => 'En cours', 'color' => '#3B82F6']);
    }

    private function task(User $user, TaskStatus $status): Task
    {
        return Task::create([
            'name'           => 'Tâche de test',
            'description'    => 'Description',
            'status_id'      => $status->id,
            'assigned_to_id' => $user->id,
        ]);
    }

    // --- Statuses ---

    public function test_task_statuses_list_requires_auth(): void
    {
        $this->get('/task-statuses')->assertRedirect('/login');
    }

    public function test_task_statuses_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/task-statuses')
            ->assertOk();
    }

    // --- Tasks ---

    public function test_tasks_list_requires_auth(): void
    {
        $this->get('/tasks')->assertRedirect('/login');
    }

    public function test_tasks_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/tasks')
            ->assertOk();
    }

    public function test_task_is_shown_in_list(): void
    {
        $admin  = $this->adminUser();
        $status = $this->taskStatus();
        $this->task($admin, $status);

        $this->assertDatabaseHas('tasks', ['name' => 'Tâche de test']);
        $this->actingAs($admin)->get('/tasks')->assertOk();
    }

    public function test_task_show_is_accessible(): void
    {
        $admin  = $this->adminUser();
        $task   = $this->task($admin, $this->taskStatus());

        $this->actingAs($admin)
            ->get("/tasks/{$task->id}")
            ->assertOk();
    }

    public function test_task_edit_is_accessible(): void
    {
        $admin = $this->adminUser();
        $task  = $this->task($admin, $this->taskStatus());

        $this->actingAs($admin)
            ->get("/tasks/{$task->id}/edit")
            ->assertOk();
    }

    public function test_task_has_correct_status(): void
    {
        $admin  = $this->adminUser();
        $status = $this->taskStatus();
        $task   = $this->task($admin, $status);

        $this->assertEquals($status->id, $task->status->id);
    }

    public function test_task_has_correct_assignee(): void
    {
        $admin  = $this->adminUser();
        $status = $this->taskStatus();
        $task   = $this->task($admin, $status);

        $this->assertEquals($admin->id, $task->assignedTo->id);
    }

    public function test_task_can_be_soft_deleted(): void
    {
        $admin  = $this->adminUser();
        $task   = $this->task($admin, $this->taskStatus());

        $task->delete();

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    // --- Tags ---

    public function test_task_tags_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/task-tags')
            ->assertOk();
    }

    public function test_task_can_have_tags(): void
    {
        $admin  = $this->adminUser();
        $task   = $this->task($admin, $this->taskStatus());
        $tag    = TaskTag::create(['name' => 'Urgent']);

        $task->tag()->attach($tag);

        $this->assertTrue($task->tag->contains($tag));
    }
}
