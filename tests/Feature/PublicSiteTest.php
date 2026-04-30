<?php

namespace Tests\Feature;

use App\Models\ContentPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_root_redirects_to_login_when_unauthenticated(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_public_page_with_builder_renders_html(): void
    {
        ContentPage::create([
            'title'        => 'Hello',
            'slug'         => 'hello-world',
            'use_builder'  => true,
            'builder_html' => '<h1 id="greeting">Bonjour</h1>',
            'builder_css'  => 'h1 { color: red; }',
        ]);

        $this->get('/p/hello-world')
            ->assertOk()
            ->assertSee('Bonjour')
            ->assertSee('id="greeting"', false);
    }

    public function test_public_page_includes_custom_css(): void
    {
        ContentPage::create([
            'title'        => 'CSS test',
            'slug'         => 'css-test',
            'use_builder'  => true,
            'builder_html' => '<p>x</p>',
            'builder_css'  => '.foo-bar-css-marker { color: blue; }',
        ]);

        $this->get('/p/css-test')
            ->assertOk()
            ->assertSee('foo-bar-css-marker', false);
    }

    public function test_public_page_returns_404_for_unknown_slug(): void
    {
        $this->get('/p/no-such-page')->assertNotFound();
    }

    public function test_public_page_escapes_title_in_output(): void
    {
        ContentPage::create([
            'title'        => '<script>alert("xss")</script>',
            'slug'         => 'xss-page',
            'use_builder'  => true,
            'builder_html' => '<p>safe</p>',
            'builder_css'  => '',
        ]);

        $response = $this->get('/p/xss-page')->assertOk();
        // raw script tag with the title content should not be present
        $response->assertDontSee('<script>alert("xss")</script>', false);
    }
}
