<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('startups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('founders');
            $table->string('faculty_advisor')->nullable();
            $table->string('technology_used')->nullable();
            $table->date('incorporation_date')->nullable();
            $table->string('funding_status');
            $table->string('funding_amount')->nullable();
            $table->string('website')->nullable();
            $table->text('description');
            $table->string('industry_sector')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('startups');
    }
};
