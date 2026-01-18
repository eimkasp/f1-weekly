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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('external_id')->unique()->nullable();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('slug')->unique();
            $table->string('code', 3)->nullable();
            $table->unsignedSmallInteger('number')->nullable();
            $table->string('nationality')->nullable();
            $table->string('country_code', 3)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('image_url')->nullable();
            $table->text('biography')->nullable();
            $table->unsignedInteger('championships')->default(0);
            $table->unsignedInteger('race_wins')->default(0);
            $table->unsignedInteger('podiums')->default(0);
            $table->unsignedInteger('pole_positions')->default(0);
            $table->unsignedInteger('fastest_laps')->default(0);
            $table->decimal('career_points', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
