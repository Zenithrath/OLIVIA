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
        Schema::create('red_flags', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();
    $table->string('title');
    $table->text('description')->nullable();
    $table->string('severity');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('red_flags');
    }
};
