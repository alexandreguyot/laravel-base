<?php

namespace Tests\Feature\Admin;

use App\Models\ContentPage;
use Tests\AdminTestCase;

class ContentPageTest extends AdminTestCase
{

    private function page(array $attrs = []): ContentPage
    {
        return ContentPage::create(array_merge([
            'title'     => 'Ma page test',
            'page_text' => '<p>Contenu de la page</p>',
        ], $attrs));
    }

    // --- List ---

    public function test_content_pages_list_requires_auth(): void
    {
        $this->get('/content-pages')->assertRedirect('/login');
    }

    public function test_content_pages_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/content-pages')
            ->assertOk();
    }

    public function test_content_page_is_shown_in_list(): void
    {
        $admin = $this->adminUser();
        $this->page(['title' => 'Page Accueil']);

        $this->assertDatabaseHas('content_pages', ['title' => 'Page Accueil']);
        $this->actingAs($admin)->get('/content-pages')->assertOk();
    }

    // --- Show / Edit ---

    public function test_content_page_show_is_accessible(): void
    {
        $admin = $this->adminUser();
        $page  = $this->page();

        $this->actingAs($admin)
            ->get("/content-pages/{$page->id}")
            ->assertOk();
    }

    public function test_content_page_edit_is_accessible(): void
    {
        $admin = $this->adminUser();
        $page  = $this->page();

        $this->actingAs($admin)
            ->get("/content-pages/{$page->id}/edit")
            ->assertOk();
    }

    // --- Page Builder ---

    public function test_page_builder_edit_requires_auth(): void
    {
        $page = $this->page();

        $this->get("/page-builder/{$page->id}/edit")
            ->assertRedirect('/login');
    }

    public function test_page_builder_edit_is_accessible(): void
    {
        $admin = $this->adminUser();
        $page  = $this->page();

        $this->actingAs($admin)
            ->get("/page-builder/{$page->id}/edit")
            ->assertOk();
    }

    public function test_page_builder_save_stores_data(): void
    {
        $admin = $this->adminUser();
        $page  = $this->page();

        $this->actingAs($admin)
            ->postJson("/page-builder/{$page->id}/save", [
                'builder_html'    => '<section><h1>Hello</h1></section>',
                'builder_css'     => 'h1 { color: red; }',
                'builder_content' => ['type' => 'wrapper', 'components' => []],
            ])
            ->assertOk()
            ->assertJsonStructure(['public_url']);

        $page->refresh();
        $this->assertTrue($page->use_builder);
        $this->assertNotNull($page->builder_html);
        $this->assertNotNull($page->slug);
    }

    public function test_page_builder_save_generates_slug_from_title(): void
    {
        $admin = $this->adminUser();
        $page  = $this->page(['title' => 'Ma Super Page']);

        $this->actingAs($admin)
            ->postJson("/page-builder/{$page->id}/save", [
                'builder_html'    => '<p>content</p>',
                'builder_css'     => '',
                'builder_content' => ['type' => 'wrapper'],
            ]);

        $this->assertNotNull($page->fresh()->slug);
    }

    // --- Public render ---

    public function test_public_page_is_rendered_when_use_builder_true(): void
    {
        ContentPage::create([
            'title'        => 'Public Page',
            'slug'         => 'my-public-page',
            'use_builder'  => true,
            'builder_html' => '<h1>Hello World</h1>',
            'builder_css'  => '',
        ]);

        $this->get('/p/my-public-page')
            ->assertOk()
            ->assertSee('Hello World');
    }

    public function test_public_page_returns_404_for_unknown_slug(): void
    {
        $this->get('/p/does-not-exist')->assertNotFound();
    }

    // --- Categories & Tags ---

    public function test_content_categories_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/content-categories')
            ->assertOk();
    }

    public function test_content_tags_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/content-tags')
            ->assertOk();
    }

    // --- Soft deletes ---

    public function test_content_page_can_be_soft_deleted(): void
    {
        $page = $this->page();
        $page->delete();

        $this->assertSoftDeleted('content_pages', ['id' => $page->id]);
    }
}
