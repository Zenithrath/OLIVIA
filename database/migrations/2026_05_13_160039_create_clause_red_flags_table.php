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
        Schema::create('clause_red_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clause_analysis_id')->constrained('clause_analyses')->cascadeOnDelete();
            $table->foreignId('red_flag_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clause_red_flags');
    }
};
