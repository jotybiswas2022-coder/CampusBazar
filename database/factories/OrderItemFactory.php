<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $price = fake()->numberBetween(100, 5000);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'seller_id' => null,
            'product_title' => fake()->words(3, true),
            'unit_price' => $price,
            'quantity' => 1,
            'line_total' => $price,
        ];
    }
}
