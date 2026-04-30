<?php

namespace Tests\Feature\Admin;

use App\Models\Course;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Test;
use App\Models\TestAnswer;
use App\Models\TestResult;
use App\Models\User;
use Tests\AdminTestCase;

class TestSystemTest extends AdminTestCase
{
    private function course(User $teacher): Course
    {
        return Course::create([
            'teacher_id'   => $teacher->id,
            'title'        => 'Cours quizz',
            'description'  => 'Desc',
            'price'        => 0,
            'is_published' => true,
        ]);
    }

    public function test_tests_list_requires_auth(): void
    {
        $this->get('/tests')->assertRedirect('/login');
    }

    public function test_tests_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/tests')
            ->assertOk();
    }

    public function test_questions_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/questions')
            ->assertOk();
    }

    public function test_question_options_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/question-options')
            ->assertOk();
    }

    public function test_test_results_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/test-results')
            ->assertOk();
    }

    public function test_test_answers_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/test-answers')
            ->assertOk();
    }

    public function test_test_can_be_created(): void
    {
        $admin  = $this->adminUser();
        $course = $this->course($admin);

        $test = Test::create([
            'course_id'    => $course->id,
            'title'        => 'Examen final',
            'description'  => 'Test sur le cours',
            'is_published' => true,
        ]);

        $this->assertDatabaseHas('tests', ['id' => $test->id, 'title' => 'Examen final']);
    }

    public function test_question_with_options_workflow(): void
    {
        $admin  = $this->adminUser();
        $course = $this->course($admin);
        // The questions.course_id FK actually references tests.id (legacy schema).
        $test = Test::create([
            'course_id'    => $course->id,
            'title'        => 'Quiz',
            'description'  => 'd',
            'is_published' => true,
        ]);

        $question = Question::create([
            'course_id'     => $test->id,
            'question_text' => 'Quelle est la capitale de la France ?',
            'points'        => 5,
        ]);

        $correct = QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Paris',
            'is_correct'  => true,
        ]);
        $wrong = QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Lyon',
            'is_correct'  => false,
        ]);

        $this->assertDatabaseHas('question_options', ['id' => $correct->id, 'is_correct' => 1]);
        $this->assertDatabaseHas('question_options', ['id' => $wrong->id, 'is_correct' => 0]);
    }

    public function test_test_result_records_a_student_score(): void
    {
        $admin   = $this->adminUser();
        $student = User::factory()->create();
        $course  = $this->course($admin);
        $test    = Test::create([
            'course_id'    => $course->id,
            'title'        => 'Quiz',
            'description'  => 'd',
            'is_published' => true,
        ]);

        $result = TestResult::create([
            'test_id'    => $test->id,
            'student_id' => $student->id,
            'score'      => 80,
        ]);

        $this->assertDatabaseHas('test_results', ['id' => $result->id, 'score' => 80]);
    }

    public function test_test_answer_can_be_recorded(): void
    {
        $admin   = $this->adminUser();
        $student = User::factory()->create();
        $course  = $this->course($admin);
        $test    = Test::create(['course_id' => $course->id, 'title' => 'Q', 'description' => 'd', 'is_published' => true]);
        $result  = TestResult::create(['test_id' => $test->id, 'student_id' => $student->id, 'score' => 0]);
        $q       = Question::create(['course_id' => $test->id, 'question_text' => 'q?', 'points' => 1]);
        $opt     = QuestionOption::create(['question_id' => $q->id, 'option_text' => 'a', 'is_correct' => true]);

        $answer = TestAnswer::create([
            'test_result_id' => $result->id,
            'question_id'    => $q->id,
            'option_id'      => $opt->id,
            'is_correct'     => true,
        ]);

        $this->assertDatabaseHas('test_answers', ['id' => $answer->id]);
    }
}
