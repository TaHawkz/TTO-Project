<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disclosures', function (Blueprint $table) {
            $table->id();
            $table->string('disclosure_id', 20)->unique()->nullable();
            $table->string('title', 500);
            $table->text('abstract');
            $table->text('description');
            $table->string('technical_field');
            $table->text('problem_solved');
            $table->text('novel_features');
            $table->text('potential_applications');
            $table->string('industry_sector');
            $table->text('existing_alternatives')->nullable();
            $table->string('funding_source')->nullable();
            $table->text('sponsor_info')->nullable();
            $table->string('project_reference')->nullable();
            $table->enum('status', [
                'draft', 'submitted', 'under_review', 'ownership_determined',
                'patentability_assessed', 'committee_review', 'approved',
                'rejected', 'patent_filing', 'commercializing',
            ])->default('draft');
            $table->foreignId('submitted_by')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('reviewer_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disclosures');
    }
};
