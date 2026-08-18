<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commercializations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patent_id')->nullable()->constrained('patents')->nullOnDelete();
            $table->foreignId('disclosure_id')->nullable()->constrained('disclosures')->nullOnDelete();
            $table->string('title', 500);
            $table->enum('type', ['licensing', 'startup', 'joint_development', 'direct_sale']);
            $table->enum('status', [
                'evaluation', 'industry_engagement', 'negotiation',
                'agreement_executed', 'active', 'closed',
            ])->default('evaluation');
            $table->string('partner_name')->nullable();
            $table->string('partner_contact')->nullable();
            $table->string('partner_email')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('managed_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commercializations');
    }
};
