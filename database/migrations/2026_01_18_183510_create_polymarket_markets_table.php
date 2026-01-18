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
        Schema::create('polymarket_markets', function (Blueprint $table) {
            $table->id();
            $table->string('condition_id')->unique()->comment('Polymarket condition ID');
            $table->string('question_id')->nullable()->comment('Polymarket question ID');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->default('f1');
            $table->string('market_type')->default('winner')->comment('winner, podium, points, etc.');
            $table->foreignId('race_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->string('outcome_yes')->nullable()->comment('Yes token description');
            $table->string('outcome_no')->nullable()->comment('No token description');
            $table->decimal('price_yes', 10, 4)->nullable()->comment('Current YES price (0-1)');
            $table->decimal('price_no', 10, 4)->nullable()->comment('Current NO price (0-1)');
            $table->decimal('volume', 20, 2)->default(0)->comment('Trading volume in USDC');
            $table->decimal('liquidity', 20, 2)->default(0)->comment('Available liquidity');
            $table->integer('volume_24h')->default(0)->comment('24h volume');
            $table->boolean('is_active')->default(true);
            $table->timestamp('end_date')->nullable();
            $table->timestamp('resolution_date')->nullable();
            $table->string('resolution')->nullable()->comment('yes, no, null');
            $table->json('tokens')->nullable()->comment('Token data from API');
            $table->json('orderbook_snapshot')->nullable()->comment('Latest orderbook data');
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
            
            $table->index(['race_id', 'is_active']);
            $table->index(['driver_id', 'is_active']);
            $table->index(['category', 'is_active']);
            $table->index('market_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polymarket_markets');
    }
};
