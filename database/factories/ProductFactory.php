<?php

namespace Database\Factories;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $title = ucwords(fake()->words(3, true));

        return [
            'user_id' => User::factory()->verified(),
            'category_id' => Category::factory(),
            'campus_id' => null,
            'title' => $title,
            'slug' => Product::generateSlug($title) . '-' . fake()->unique()->numberBetween(1, 99999),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(100, 5000),
            'condition' => ProductCondition::GOOD,
            'status' => ProductStatus::ACTIVE,
            'approved_at' => now(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => ProductStatus::ACTIVE]);
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => ProductStatus::PENDING_APPROVAL, 'approved_at' => null]);
    }

    public function sold(): static
    {
        return $this->state(fn () => ['status' => ProductStatus::SOLD]);
    }

    public function forSeller(User $seller): static
    {
        return $this->state(fn () => ['user_id' => $seller->id]);
    }
}
