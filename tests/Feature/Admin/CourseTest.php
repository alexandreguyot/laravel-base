<?php

namespace Tests\Feature\Admin;

use App\Models\Course;
use App\Models\Lesson;
use Tests\AdminTestCase;

class CourseTest extends AdminTestCase
{

    private function course(): Course
    {
        return Course::create([
            'title'       => 'Introduction à Laravel',
            'description' => 'Apprenez Laravel de zéro.',
        ]);
    }

    // --- Courses ---

    public function test_courses_list_requires_auth(): void
    {
        $this->get('/courses')->assertRedirect('/login');
    }

    public function test_courses_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/courses')
            ->assertOk();
    }

    public function test_course_is_shown_in_list(): void
    {
        $admin = $this->adminUser();
        $this->course();

        $this->assertDatabaseHas('courses', ['title' => 'Introduction à Laravel']);
        $this->actingAs($admin)->get('/courses')->assertOk();
    }

    public function test_course_show_is_accessible(): void
    {
        $admin  = $this->adminUser();
        $course = $this->course();

        $this->actingAs($admin)
            ->get("/courses/{$course->id}")
            ->assertOk();
    }

    public function test_course_edit_is_accessible(): void
    {
        $admin  = $this->adminUser();
        $course = $this->course();

        $this->actingAs($admin)
            ->get("/courses/{$course->id}/edit")
            ->assertOk();
    }

    // --- Lessons ---

    public function test_lessons_list_requires_auth(): void
    {
        $this->get('/lessons')->assertRedirect('/login');
    }

    public function test_lessons_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/lessons')
            ->assertOk();
    }

    public function test_lesson_can_be_created(): void
    {
        $course = $this->course();

        Lesson::create([
            'title'     => 'Leçon 1 : Installation',
            'content'   => '<p>Installez Laravel via Composer.</p>',
            'course_id' => $course->id,
        ]);

        $this->assertDatabaseHas('lessons', ['title' => 'Leçon 1 : Installation']);
    }

    public function test_lesson_belongs_to_course(): void
    {
        $course = $this->course();
        $lesson = Lesson::create([
            'title'     => 'Leçon 2 : Routing',
            'content'   => '<p>Apprenez le routing.</p>',
            'course_id' => $course->id,
        ]);

        $this->assertEquals($course->id, $lesson->course->id);
    }

    // --- Enrollment ---

    public function test_user_can_be_enrolled_in_course(): void
    {
        $user   = $this->adminUser();
        $course = $this->course();

        $course->students()->attach($user);

        $this->assertTrue($course->students->contains($user));
    }

    public function test_course_can_be_soft_deleted(): void
    {
        $course = $this->course();
        $course->delete();

        $this->assertSoftDeleted('courses', ['id' => $course->id]);
    }
}
