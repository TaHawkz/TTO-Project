<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disclosure_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disclosure_id')->constrained('disclosures')->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->string('filename');
            $table->string('original_name');
            $table->string('mime_type', 100);
            $table->bigInteger('file_size');
            $table->string('path', 500);
            $table->enum('document_type', [
                'disclosure_form', 'drawing', 'supporting_data', 'other',
            ])->default('other');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disclosure_documents');
    }
};
