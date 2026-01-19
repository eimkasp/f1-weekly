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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('favorite_driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->integer('survival_high_score')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['favorite_driver_id']);
            $table->dropColumn(['favorite_driver_id', 'survival_high_score']);
        });
    }
};
