<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revenue_records', function (Blueprint $table) {
            $table->id();
            $table->enum('source_type', ['licensing', 'royalty', 'milestone', 'other']);
            $table->foreignId('agreement_id')->nullable()->constrained('agreements')->nullOnDelete();
            $table->foreignId('disclosure_id')->nullable()->constrained('disclosures')->nullOnDelete();
            $table->foreignId('patent_id')->nullable()->constrained('patents')->nullOnDelete();
            $table->decimal('gross_amount', 14, 2);
            $table->decimal('deductions', 14, 2)->default(0);
            $table->decimal('net_amount', 14, 2);
            $table->date('received_date');
            $table->string('currency', 10)->default('BDT');
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revenue_records');
    }
};
