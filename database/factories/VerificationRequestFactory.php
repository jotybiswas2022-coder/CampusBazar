<?php

namespace Database\Factories;

use App\Enums\VerificationStatus;
use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VerificationRequest>
 */
class VerificationRequestFactory extends Factory
{
    protected $model = VerificationRequest::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'full_name' => fake()->name(),
            'institution_name' => fake()->company() . ' College',
            'department' => fake()->randomElement(['Science', 'Business', 'Arts']),
            'student_id_number' => fake()->numerify('ID-#####'),
            'academic_year' => '2024-2025',
            'front_image_path' => 'verification/sample/front.jpg',
            'back_image_path' => null,
            'status' => VerificationStatus::PENDING,
        ];
    }
}
