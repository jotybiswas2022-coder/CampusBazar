<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderWorkflow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class OrderWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_order_cannot_skip_straight_to_delivered(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::PENDING_CONFIRMATION]);
        $admin = User::factory()->admin()->create();

        $this->expectException(ValidationException::class);

        app(OrderWorkflow::class)->transition($order, OrderStatus::DELIVERED, $admin);
    }

    public function test_an_order_follows_the_allowed_state_machine(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::PENDING_CONFIRMATION]);
        $admin = User::factory()->admin()->create();
        $workflow = app(OrderWorkflow::class);

        $workflow->transition($order, OrderStatus::CONFIRMED, $admin);
        $workflow->transition($order, OrderStatus::PROCESSING, $admin);
        $workflow->transition($order, OrderStatus::SHIPPED, $admin);
        $workflow->transition($order, OrderStatus::OUT_FOR_DELIVERY, $admin);
        $workflow->transition($order, OrderStatus::DELIVERED, $admin);

        $this->assertSame(OrderStatus::DELIVERED, $order->fresh()->status);
        $this->assertCount(5, $order->statusHistories);
    }

    public function test_delivering_an_order_marks_the_product_sold(): void
    {
        $product = Product::factory()->create(['status' => 'reserved']);
        $order = Order::factory()->create(['status' => OrderStatus::OUT_FOR_DELIVERY]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);
        $admin = User::factory()->admin()->create();

        app(OrderWorkflow::class)->transition($order, OrderStatus::DELIVERED, $admin);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => 'sold',
        ]);
    }

    public function test_cancelling_an_order_returns_the_product_to_active(): void
    {
        $product = Product::factory()->create(['status' => 'reserved']);
        $order = Order::factory()->create(['status' => OrderStatus::PENDING_CONFIRMATION]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);
        $buyer = $order->buyer;

        app(OrderWorkflow::class)->transition($order, OrderStatus::CANCELLED, $buyer, 'Changed my mind.');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::CANCELLED,
        ]);
    }

    public function test_cod_payment_cannot_be_confirmed_before_delivery(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::CONFIRMED]);
        $admin = User::factory()->admin()->create();

        $this->expectException(ValidationException::class);

        app(OrderWorkflow::class)->confirmCodPayment($order, $admin);
    }

    public function test_cod_payment_is_confirmed_after_delivery(): void
    {
        $order = Order::factory()->create([
            'status' => OrderStatus::DELIVERED,
            'payment_status' => 'unpaid',
        ]);
        $admin = User::factory()->admin()->create();

        app(OrderWorkflow::class)->confirmCodPayment($order, $admin);

        $order->refresh();
        $this->assertSame('paid', $order->payment_status);
        $this->assertSame($admin->id, $order->payment_confirmed_by);
        $this->assertNotNull($order->payment_collected_at);
    }
}
