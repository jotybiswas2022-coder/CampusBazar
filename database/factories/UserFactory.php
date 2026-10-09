<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Campus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '01' . fake()->numerify('#########'),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => UserRole::STUDENT,
            'campus_id' => null,
            'status' => 'active',
            'verified_at' => null,
        ];
    }

    public function unverifiedEmail(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'role' => UserRole::ADMIN,
            'is_admin' => true,
            'verified_at' => now(),
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn () => ['verified_at' => now()]);
    }

    public function forCampus(?Campus $campus = null): static
    {
        return $this->state(fn () => [
            'campus_id' => $campus?->id ?? Campus::factory(),
        ]);
    }
}
