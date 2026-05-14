<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vector_chunks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('legal_document_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('chunk_index')->default(0);
            $table->longText('content');
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vector_chunks');
    }
};
