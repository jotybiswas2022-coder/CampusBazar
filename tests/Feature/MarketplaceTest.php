<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        $this->get(route('home'))->assertOk()->assertSee('CampusBazar');
    }

    public function test_marketplace_lists_active_products_only(): void
    {
        $active = Product::factory()->create(['title' => 'Visible Textbook', 'status' => 'active']);
        $pending = Product::factory()->pending()->create(['title' => 'Hidden Draft']);

        $response = $this->get(route('marketplace.index'));

        $response->assertOk();
        $response->assertSee('Visible Textbook');
        $response->assertDontSee('Hidden Draft');
    }

    public function test_marketplace_can_be_filtered_by_search_term(): void
    {
        Product::factory()->create(['title' => 'Casio Calculator']);
        Product::factory()->create(['title' => 'Hostel Fan']);

        $response = $this->get(route('marketplace.index', ['q' => 'Calculator']));

        $response->assertOk();
        $response->assertSee('Casio Calculator');
        $response->assertDontSee('Hostel Fan');
    }

    public function test_product_detail_page_loads_for_a_guest(): void
    {
        $product = Product::factory()->create(['status' => 'active']);

        $this->get(route('products.show', $product))->assertOk()->assertSee($product->title);
    }

    public function test_a_verified_student_can_toggle_a_wishlist_item(): void
    {
        $student = User::factory()->verified()->create();
        $product = Product::factory()->create(['status' => 'active']);

        $this->actingAs($student)
            ->post(route('wishlist.toggle', $product))
            ->assertRedirect();

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $student->id,
            'product_id' => $product->id,
        ]);

        $this->actingAs($student)
            ->post(route('wishlist.toggle', $product))
            ->assertRedirect();

        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $student->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_category_page_shows_only_products_in_that_category(): void
    {
        $books = Category::factory()->create(['name' => 'Books', 'slug' => 'books']);
        $gadgets = Category::factory()->create(['name' => 'Gadgets', 'slug' => 'gadgets']);

        Product::factory()->create(['title' => 'Math Book', 'category_id' => $books->id]);
        Product::factory()->create(['title' => 'Headphones', 'category_id' => $gadgets->id]);

        $response = $this->get(route('marketplace.category', $books));

        $response->assertOk();
        $response->assertSee('Math Book');
        $response->assertDontSee('Headphones');
    }
}
