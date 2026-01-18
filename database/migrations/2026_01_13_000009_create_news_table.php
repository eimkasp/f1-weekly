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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->text('summary')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('featured_image_alt')->nullable();
            $table->enum('category', ['race', 'qualifying', 'transfer', 'analysis', 'preview', 'review', 'technical', 'breaking', 'feature'])->default('race');
            $table->enum('status', ['draft', 'pending_review', 'published', 'archived'])->default('draft');
            $table->boolean('ai_generated')->default(false);
            $table->string('ai_model')->nullable();
            $table->decimal('sentiment_score', 3, 2)->nullable(); // -1 to 1
            $table->enum('sentiment_label', ['negative', 'neutral', 'positive'])->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->json('structured_data')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index(['category', 'published_at']);
        });

        // Pivot tables for news relationships
        Schema::create('driver_news', function (Blueprint $table) {
            $table->foreignId('driver_id')->constrained()->cascadeOnDelete();
            $table->foreignId('news_id')->constrained()->cascadeOnDelete();
            $table->primary(['driver_id', 'news_id']);
        });

        Schema::create('team_news', function (Blueprint $table) {
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('news_id')->constrained()->cascadeOnDelete();
            $table->primary(['team_id', 'news_id']);
        });

        Schema::create('race_news', function (Blueprint $table) {
            $table->foreignId('race_id')->constrained()->cascadeOnDelete();
            $table->foreignId('news_id')->constrained()->cascadeOnDelete();
            $table->primary(['race_id', 'news_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('race_news');
        Schema::dropIfExists('team_news');
        Schema::dropIfExists('driver_news');
        Schema::dropIfExists('news');
    }
};
