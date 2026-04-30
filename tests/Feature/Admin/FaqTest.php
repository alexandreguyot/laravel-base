<?php

namespace Tests\Feature\Admin;

use App\Models\FaqCategory;
use App\Models\FaqQuestion;
use Tests\AdminTestCase;

class FaqTest extends AdminTestCase
{

    private function category(): FaqCategory
    {
        return FaqCategory::create(['category' => 'Général']);
    }

    // --- Categories ---

    public function test_faq_categories_list_requires_auth(): void
    {
        $this->get('/faq-categories')->assertRedirect('/login');
    }

    public function test_faq_categories_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/faq-categories')
            ->assertOk();
    }

    public function test_faq_category_is_shown_in_list(): void
    {
        $admin = $this->adminUser();
        $this->category();

        $this->assertDatabaseHas('faq_categories', ['category' => 'Général']);
        $this->actingAs($admin)->get('/faq-categories')->assertOk();
    }

    public function test_faq_category_show_is_accessible(): void
    {
        $admin    = $this->adminUser();
        $category = $this->category();

        $this->actingAs($admin)
            ->get("/faq-categories/{$category->id}")
            ->assertOk();
    }

    // --- Questions ---

    public function test_faq_questions_list_requires_auth(): void
    {
        $this->get('/faq-questions')->assertRedirect('/login');
    }

    public function test_faq_questions_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/faq-questions')
            ->assertOk();
    }

    public function test_faq_question_is_shown_in_list(): void
    {
        $admin    = $this->adminUser();
        $category = $this->category();

        FaqQuestion::create([
            'category_id' => $category->id,
            'question'    => 'Comment créer un compte ?',
            'answer'      => 'Cliquez sur Inscription.',
        ]);

        $this->assertDatabaseHas('faq_questions', ['question' => 'Comment créer un compte ?']);
        $this->actingAs($admin)->get('/faq-questions')->assertOk();
    }

    public function test_faq_question_belongs_to_category(): void
    {
        $category = $this->category();
        $question = FaqQuestion::create([
            'category_id' => $category->id,
            'question'    => 'Une question ?',
            'answer'      => 'Une réponse.',
        ]);

        $this->assertEquals($category->id, $question->category->id);
    }

    public function test_faq_question_show_is_accessible(): void
    {
        $admin    = $this->adminUser();
        $category = $this->category();
        $question = FaqQuestion::create([
            'category_id' => $category->id,
            'question'    => 'Question test ?',
            'answer'      => 'Réponse test.',
        ]);

        $this->actingAs($admin)
            ->get("/faq-questions/{$question->id}")
            ->assertOk();
    }
}
