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
        Schema::create('constructor_standings', function (Blueprint $table) {
            $table->id();
            $table->year('season');
            $table->unsignedTinyInteger('round');
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('position');
            $table->decimal('points', 6, 1)->default(0);
            $table->unsignedTinyInteger('wins')->default(0);
            $table->timestamps();

            $table->unique(['season', 'round', 'team_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('constructor_standings');
    }
};
