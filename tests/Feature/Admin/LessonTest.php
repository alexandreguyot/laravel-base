<?php

namespace Tests\Feature\Admin;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Tests\AdminTestCase;

class LessonTest extends AdminTestCase
{
    private function course(User $teacher): Course
    {
        return Course::create([
            'teacher_id'   => $teacher->id,
            'title'        => 'Cours de test',
            'description'  => 'Desc',
            'price'        => 0,
            'is_published' => true,
        ]);
    }

    private function lesson(Course $course): Lesson
    {
        return Lesson::create([
            'course_id'    => $course->id,
            'title'        => 'Leçon 1',
            'short_text'   => 'Intro',
            'long_text'    => 'Contenu',
            'position'     => 1,
            'is_published' => true,
            'is_free'      => false,
        ]);
    }

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

    public function test_lesson_can_be_created_inside_course(): void
    {
        $admin  = $this->adminUser();
        $course = $this->course($admin);
        $this->lesson($course);

        $this->assertDatabaseHas('lessons', [
            'title'     => 'Leçon 1',
            'course_id' => $course->id,
        ]);
    }

    public function test_lesson_show_is_accessible(): void
    {
        $admin  = $this->adminUser();
        $course = $this->course($admin);
        $lesson = $this->lesson($course);

        $this->actingAs($admin)
            ->get("/lessons/{$lesson->id}")
            ->assertOk();
    }

    public function test_lesson_belongs_to_course(): void
    {
        $admin  = $this->adminUser();
        $course = $this->course($admin);
        $lesson = $this->lesson($course);

        $this->assertEquals($course->id, $lesson->course->id);
    }

    public function test_lesson_can_be_soft_deleted(): void
    {
        $admin  = $this->adminUser();
        $course = $this->course($admin);
        $lesson = $this->lesson($course);

        $lesson->delete();

        $this->assertSoftDeleted('lessons', ['id' => $lesson->id]);
    }
}
