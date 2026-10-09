<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminModerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_the_admin_area(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_non_admins_are_forbidden_from_the_admin_area(): void
    {
        $student = User::factory()->verified()->create();

        $this->actingAs($student)
            ->get(route('admin.dashboard'))
            ->assertStatus(403);
    }

    public function test_an_admin_can_view_the_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_an_admin_can_approve_a_pending_listing(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->pending()->create();

        $this->actingAs($admin)
            ->post(route('admin.products.approve', $product))
            ->assertRedirect();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => 'active',
        ]);
    }

    public function test_an_admin_can_reject_a_listing_with_a_reason(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->pending()->create();

        $this->actingAs($admin)
            ->post(route('admin.products.reject', $product), [
                'rejection_reason' => 'Prohibited item.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => 'rejected',
            'rejection_reason' => 'Prohibited item.',
        ]);
    }

    public function test_rejecting_a_listing_requires_a_reason(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->pending()->create();

        $this->actingAs($admin)
            ->from(route('admin.products.show', $product))
            ->post(route('admin.products.reject', $product), [])
            ->assertSessionHasErrors('rejection_reason');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => 'pending_approval',
        ]);
    }

    public function test_an_admin_can_suspend_a_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->verified()->create();

        $this->actingAs($admin)
            ->patch(route('admin.users.status', $user), ['status' => 'suspended'])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'suspended',
        ]);
    }
}
