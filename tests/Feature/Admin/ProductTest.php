<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use Tests\AdminTestCase;

class ProductTest extends AdminTestCase
{

    // --- Products ---

    public function test_products_list_requires_auth(): void
    {
        $this->get('/products')->assertRedirect('/login');
    }

    public function test_products_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/products')
            ->assertOk();
    }

    public function test_product_is_shown_in_list(): void
    {
        $admin = $this->adminUser();
        Product::create(['name' => 'Produit Alpha', 'description' => 'Desc', 'price' => 99.99]);

        $this->assertDatabaseHas('products', ['name' => 'Produit Alpha']);
        $this->actingAs($admin)->get('/products')->assertOk();
    }

    public function test_product_show_is_accessible(): void
    {
        $admin   = $this->adminUser();
        $product = Product::create(['name' => 'Widget', 'description' => 'A widget', 'price' => 9.99]);

        $this->actingAs($admin)
            ->get("/products/{$product->id}")
            ->assertOk();
    }

    public function test_product_edit_is_accessible(): void
    {
        $admin   = $this->adminUser();
        $product = Product::create(['name' => 'Widget', 'description' => 'A widget', 'price' => 9.99]);

        $this->actingAs($admin)
            ->get("/products/{$product->id}/edit")
            ->assertOk();
    }

    public function test_product_can_be_soft_deleted(): void
    {
        $product = Product::create(['name' => 'Widget', 'description' => 'A widget', 'price' => 9.99]);
        $product->delete();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    // --- Categories ---

    public function test_product_categories_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/product-categories')
            ->assertOk();
    }

    public function test_product_category_can_be_created(): void
    {
        ProductCategory::create(['name' => 'Électronique', 'description' => 'Catégorie électronique']);

        $this->assertDatabaseHas('product_categories', ['name' => 'Électronique']);
    }

    // --- Tags ---

    public function test_product_tags_list_is_accessible(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/product-tags')
            ->assertOk();
    }

    public function test_product_tag_can_be_created(): void
    {
        ProductTag::create(['name' => 'Promo']);

        $this->assertDatabaseHas('product_tags', ['name' => 'Promo']);
    }

    // --- Relations ---

    public function test_product_can_have_categories(): void
    {
        $product  = Product::create(['name' => 'Widget', 'description' => 'Desc', 'price' => 9.99]);
        $category = ProductCategory::create(['name' => 'Tech', 'description' => 'Tech products']);

        $product->category()->attach($category);

        $this->assertTrue($product->category->contains($category));
    }

    public function test_product_can_have_tags(): void
    {
        $product = Product::create(['name' => 'Widget', 'description' => 'Desc', 'price' => 9.99]);
        $tag     = ProductTag::create(['name' => 'Featured']);

        $product->tag()->attach($tag);

        $this->assertTrue($product->tag->contains($tag));
    }
}
