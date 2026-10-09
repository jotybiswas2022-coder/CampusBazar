<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->string('recipient_name');
            $table->string('phone');
            $table->text('address');
            $table->string('district');
            $table->string('upazila')->nullable();
            $table->text('delivery_instructions')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('payment_method')->default('cod');
            $table->string('payment_status')->default('unpaid');
            $table->string('status')->default('pending_confirmation');
            $table->timestamp('payment_collected_at')->nullable();
            $table->foreignId('payment_confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('payment_status');
            $table->index('buyer_id');
            $table->index('seller_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
