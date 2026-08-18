<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disclosure_id')->nullable()->constrained('disclosures')->nullOnDelete();
            $table->string('title', 500);
            $table->string('patent_number', 100)->nullable();
            $table->enum('status', [
                'draft', 'filed', 'published', 'examination', 'granted', 'expired', 'abandoned',
            ])->default('draft');
            $table->string('jurisdiction', 10)->default('BD');
            $table->date('filing_date')->nullable();
            $table->date('publication_date')->nullable();
            $table->date('grant_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('applicant');
            $table->string('attorney_firm')->nullable();
            $table->string('attorney_contact')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('managed_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patents');
    }
};
