<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revenue_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('revenue_record_id')->constrained('revenue_records')->cascadeOnDelete();
            $table->enum('recipient_type', ['inventor', 'department', 'university']);
            $table->string('recipient_name');
            $table->foreignId('recipient_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('percentage', 5, 2);
            $table->decimal('amount', 14, 2);
            $table->enum('payment_status', ['pending', 'paid'])->default('pending');
            $table->date('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revenue_distributions');
    }
};
