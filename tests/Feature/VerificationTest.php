<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_student_can_view_the_verification_status_page(): void
    {
        $student = User::factory()->create();

        $this->actingAs($student)
            ->get(route('verification.status'))
            ->assertOk();
    }

    public function test_an_unverified_student_cannot_access_the_seller_area(): void
    {
        $student = User::factory()->create();

        $this->actingAs($student)
            ->get(route('seller.products.create'))
            ->assertRedirect(route('verification.status'));
    }

    public function test_a_verified_student_can_access_the_seller_area(): void
    {
        $student = User::factory()->verified()->create();

        $this->actingAs($student)
            ->get(route('seller.products.create'))
            ->assertOk();
    }

    public function test_a_student_with_a_pending_request_cannot_resubmit(): void
    {
        $student = User::factory()->create();
        VerificationRequest::factory()->create([
            'user_id' => $student->id,
            'status' => 'pending',
        ]);

        $this->actingAs($student)
            ->get(route('verification.create'))
            ->assertRedirect(route('verification.status'));
    }

    public function test_an_admin_can_approve_a_verification_and_verify_the_student(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->create();
        $verification = VerificationRequest::factory()->create([
            'user_id' => $student->id,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.verifications.approve', $verification))
            ->assertRedirect(route('admin.verifications.show', $verification));

        $this->assertDatabaseHas('verification_requests', [
            'id' => $verification->id,
            'status' => 'approved',
        ]);

        $this->assertNotNull($student->fresh()->verified_at);
    }

    public function test_a_student_cannot_view_another_students_document(): void
    {
        $owner = User::factory()->create();
        $verification = VerificationRequest::factory()->create([
            'user_id' => $owner->id,
            'status' => 'pending',
            'front_image_path' => 'verification/sample/front.jpg',
        ]);
        $intruder = User::factory()->create();

        $this->actingAs($intruder)
            ->get(route('verification.document', [$verification, 'front']))
            ->assertStatus(403);
    }
}
