<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('full_name');
            $table->string('institution_name');
            $table->string('department')->nullable();
            $table->string('student_id_number');
            $table->string('academic_year')->nullable();
            $table->string('front_image_path');
            $table->string('back_image_path')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('review_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('resubmission_reason')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_requests');
    }
};
