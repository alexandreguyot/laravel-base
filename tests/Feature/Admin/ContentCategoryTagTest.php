<?php

namespace Tests\Feature\Admin;

use App\Models\ContentCategory;
use App\Models\ContentTag;
use Tests\AdminTestCase;

class ContentCategoryTagTest extends AdminTestCase
{
    public function test_content_categories_index_requires_auth(): void
    {
        $this->get('/content-categories')->assertRedirect('/login');
    }

    public function test_content_categories_index_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/content-categories')
            ->assertOk();
    }

    public function test_content_category_can_be_created(): void
    {
        ContentCategory::create(['name' => 'Blog', 'slug' => 'blog']);

        $this->assertDatabaseHas('content_categories', ['slug' => 'blog']);
    }

    public function test_content_category_show_is_accessible(): void
    {
        $admin    = $this->adminUser();
        $category = ContentCategory::create(['name' => 'Tutoriels', 'slug' => 'tutoriels']);

        $this->actingAs($admin)
            ->get("/content-categories/{$category->id}")
            ->assertOk();
    }

    public function test_content_tags_index_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/content-tags')
            ->assertOk();
    }

    public function test_content_tag_can_be_created(): void
    {
        ContentTag::create(['name' => 'Featured', 'slug' => 'featured']);

        $this->assertDatabaseHas('content_tags', ['slug' => 'featured']);
    }

    public function test_content_tag_show_is_accessible(): void
    {
        $admin = $this->adminUser();
        $tag   = ContentTag::create(['name' => 'Tips', 'slug' => 'tips']);

        $this->actingAs($admin)
            ->get("/content-tags/{$tag->id}")
            ->assertOk();
    }

}
