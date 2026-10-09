<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_unverified_student_is_redirected_away_from_checkout(): void
    {
        $seller = User::factory()->verified()->create();
        $product = Product::factory()->forSeller($seller)->create(['status' => 'active']);
        $buyer = User::factory()->create();

        $this->actingAs($buyer)
            ->get(route('checkout.create', $product))
            ->assertRedirect(route('verification.status'));
    }

    public function test_unverified_student_cannot_post_an_order(): void
    {
        $seller = User::factory()->verified()->create();
        $product = Product::factory()->forSeller($seller)->create(['status' => 'active']);
        $buyer = User::factory()->create();

        $this->actingAs($buyer)
            ->post(route('checkout.store', $product), [
                'recipient_name' => 'Buyer',
                'phone' => '01712345678',
                'address' => 'Somewhere',
                'district' => 'Dhaka',
            ])
            ->assertRedirect(route('verification.status'));

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_a_verified_student_can_place_a_cod_order(): void
    {
        $seller = User::factory()->verified()->create();
        $product = Product::factory()->forSeller($seller)->create(['status' => 'active', 'price' => 1000]);
        $buyer = User::factory()->verified()->create();

        $response = $this->actingAs($buyer)->post(route('checkout.store', $product), [
            'recipient_name' => 'Campus Buyer',
            'phone' => '01712345678',
            'address' => 'Hall Road, Room 101',
            'district' => 'Dhaka',
            'upazila' => 'Mirpur',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
            'status' => 'pending_confirmation',
            'subtotal' => 1000,
            'delivery_charge' => 60,
            'total' => 1060,
        ]);

        // The product is reserved while the order is pending.
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => 'reserved',
        ]);

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertEquals(1, $order->items()->count());
    }

    public function test_a_seller_cannot_buy_their_own_listing(): void
    {
        $seller = User::factory()->verified()->create();
        $product = Product::factory()->forSeller($seller)->create(['status' => 'active']);

        $this->actingAs($seller)
            ->get(route('checkout.create', $product))
            ->assertStatus(403);
    }

    public function test_a_reserved_product_can_no_longer_be_checked_out(): void
    {
        $seller = User::factory()->verified()->create();
        $product = Product::factory()->forSeller($seller)->create(['status' => 'reserved']);
        $buyer = User::factory()->verified()->create();

        $this->actingAs($buyer)
            ->get(route('checkout.create', $product))
            ->assertNotFound();
    }
}
