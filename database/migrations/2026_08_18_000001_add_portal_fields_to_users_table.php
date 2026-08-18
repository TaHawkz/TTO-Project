<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'student', 'faculty', 'staff', 'reviewer',
                'tto_officer', 'legal_officer', 'director', 'system_admin',
            ])->default('student')->after('password');
            $table->string('department')->nullable()->after('role');
            $table->string('designation')->nullable()->after('department');
            $table->string('phone', 50)->nullable()->after('designation');
            $table->boolean('is_active')->default(true)->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'department', 'designation', 'phone', 'is_active']);
        });
    }
};
