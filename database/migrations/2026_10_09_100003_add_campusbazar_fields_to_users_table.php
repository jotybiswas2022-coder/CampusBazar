<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar_path')->nullable()->after('password');
            $table->text('bio')->nullable()->after('avatar_path');
            $table->foreignId('campus_id')->nullable()->after('bio')->constrained('campuses')->nullOnDelete();
            $table->string('role')->default('student')->after('campus_id');
            $table->timestamp('verified_at')->nullable()->after('role');
            $table->timestamp('last_seen_at')->nullable()->after('verified_at');
            $table->string('status')->default('active')->after('last_seen_at');
            $table->index('role');
            $table->index('verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['campus_id']);
            $table->dropColumn([
                'phone', 'avatar_path', 'bio', 'campus_id', 'role',
                'verified_at', 'last_seen_at', 'status',
            ]);
        });
    }
};
