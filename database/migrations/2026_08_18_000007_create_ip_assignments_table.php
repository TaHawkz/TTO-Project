<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ip_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disclosure_id')->constrained('disclosures');
            $table->enum('outcome', ['university', 'inventor', 'joint', 'sponsored_research']);
            $table->date('determination_date')->nullable();
            $table->foreignId('determined_by')->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ip_assignments');
    }
};
