<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patent_deadlines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patent_id')->constrained('patents')->cascadeOnDelete();
            $table->string('deadline_type');
            $table->date('due_date');
            $table->boolean('is_completed')->default(false);
            $table->date('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patent_deadlines');
    }
};
