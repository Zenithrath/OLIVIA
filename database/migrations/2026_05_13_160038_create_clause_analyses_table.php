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
        Schema::create('clause_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_clause_id')->constrained('contract_clauses')->cascadeOnDelete();
            $table->foreignId('scan_history_id')->nullable()->constrained('scan_histories')->nullOnDelete();
            $table->string('status')->default('completed');
            $table->string('severity')->nullable();
            $table->decimal('confidence_score', 8, 4)->nullable();
            $table->longText('analysis');
            $table->text('legal_basis')->nullable();
            $table->text('suggestion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clause_analyses');
    }
};
