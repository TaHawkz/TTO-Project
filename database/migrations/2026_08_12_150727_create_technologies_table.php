<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technologies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('industry_sector');
            $table->enum('development_stage', ['early_stage', 'filed', 'granted', 'licensed'])->default('early_stage');
            $table->json('benefits');
            $table->boolean('licensing_available')->default(false);
            $table->string('contact_email')->default('office.tto@northsouth.edu');
            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technologies');
    }
};
