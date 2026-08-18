<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500);
            $table->enum('type', [
                'assignment', 'nda_cda', 'revenue_sharing',
                'sponsored_research', 'licensing', 'other',
            ]);
            $table->foreignId('disclosure_id')->nullable()->constrained('disclosures')->nullOnDelete();
            $table->foreignId('patent_id')->nullable()->constrained('patents')->nullOnDelete();
            $table->json('parties');
            $table->date('signed_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', [
                'draft', 'under_review', 'signed', 'expired', 'terminated',
            ])->default('draft');
            $table->string('document_path', 500)->nullable();
            $table->foreignId('managed_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agreements');
    }
};
