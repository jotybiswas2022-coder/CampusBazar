<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_view_login_and_register_pages(): void
    {
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();
    }

    public function test_a_student_can_register_and_is_redirected_to_verification(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'New Student',
            'email' => 'new.student@example.com',
            'phone' => '01712345678',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('verification.status'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'new.student@example.com',
            'role' => 'student',
        ]);
    }

    public function test_registration_requires_accepting_terms(): void
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'No Terms',
            'email' => 'no.terms@example.com',
            'phone' => '01712345678',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('terms');
        $this->assertGuest();
    }

    public function test_a_verified_student_can_log_in(): void
    {
        $user = User::factory()->verified()->create(['password' => bcrypt('password')]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_a_suspended_user_cannot_log_in(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'status' => 'suspended',
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_an_admin_is_redirected_to_the_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create(['password' => bcrypt('password')]);

        $response = $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }
}
