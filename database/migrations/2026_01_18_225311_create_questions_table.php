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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->json('options'); // Array of 4 options
            $table->string('correct_answer'); // The correct option string or index
            $table->string('explanation')->nullable();
            $table->string('category')->default('general'); // history, drivers, technical, circuits
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
