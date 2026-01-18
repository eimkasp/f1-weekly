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
        Schema::create('races', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('external_id')->unique()->nullable();
            $table->foreignId('circuit_id')->nullable()->constrained()->cascadeOnDelete();
            $table->year('season');
            $table->unsignedTinyInteger('round');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('official_name')->nullable();
            $table->dateTime('race_date');
            $table->string('timezone')->default('UTC');
            $table->enum('status', ['scheduled', 'live', 'completed', 'cancelled', 'postponed'])->default('scheduled');
            $table->unsignedSmallInteger('laps')->nullable();
            $table->string('distance')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_sprint_weekend')->default(false);
            $table->timestamps();

            $table->unique(['season', 'round']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('races');
    }
};
