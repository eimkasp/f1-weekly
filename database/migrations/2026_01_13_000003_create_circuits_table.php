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
        Schema::create('circuits', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('external_id')->unique()->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('city')->nullable();
            $table->string('country');
            $table->string('country_code', 3)->nullable();
            $table->decimal('length', 6, 3)->nullable(); // km
            $table->unsignedTinyInteger('corners')->nullable();
            $table->unsignedTinyInteger('drs_zones')->nullable();
            $table->string('lap_record')->nullable();
            $table->string('lap_record_holder')->nullable();
            $table->year('lap_record_year')->nullable();
            $table->string('image')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->year('first_grand_prix')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('circuits');
    }
};
