<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->numberBetween(100, 5000);
        $delivery = 60;

        return [
            'order_number' => Order::generateOrderNumber(),
            'buyer_id' => User::factory()->verified(),
            'seller_id' => User::factory()->verified(),
            'recipient_name' => fake()->name(),
            'phone' => '01' . fake()->numerify('#########'),
            'address' => fake()->address(),
            'district' => 'Dhaka',
            'upazila' => 'Mirpur',
            'subtotal' => $subtotal,
            'delivery_charge' => $delivery,
            'total' => $subtotal + $delivery,
            'payment_method' => 'cod',
            'payment_status' => PaymentStatus::UNPAID,
            'status' => OrderStatus::PENDING_CONFIRMATION,
        ];
    }
}
