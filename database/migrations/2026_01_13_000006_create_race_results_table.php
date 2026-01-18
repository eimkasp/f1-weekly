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
        Schema::create('race_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('race_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('position')->nullable();
            $table->unsignedTinyInteger('grid_position')->nullable();
            $table->decimal('points', 4, 1)->default(0);
            $table->unsignedSmallInteger('laps_completed')->nullable();
            $table->string('time')->nullable(); // Race time
            $table->string('fastest_lap')->nullable();
            $table->unsignedSmallInteger('fastest_lap_number')->nullable();
            $table->boolean('has_fastest_lap')->default(false);
            $table->enum('status', ['finished', 'DNF', 'DNS', 'DSQ', '+1 Lap', '+2 Laps', '+3 Laps'])->nullable();
            $table->string('status_detail')->nullable(); // e.g., "Engine", "Collision"
            $table->timestamps();

            $table->unique(['race_id', 'driver_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('race_results');
    }
};
