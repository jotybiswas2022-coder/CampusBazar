<?php

namespace Database\Factories;

use App\Models\Campus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campus>
 */
class CampusFactory extends Factory
{
    protected $model = Campus::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company() . ' College',
            'type' => fake()->randomElement(['college', 'university', 'institute']),
            'district' => fake()->randomElement(['Dhaka', 'Chattogram', 'Rajshahi', 'Khulna', 'Sylhet']),
            'location' => fake()->address(),
            'is_active' => true,
        ];
    }
}
