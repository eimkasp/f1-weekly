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
        Schema::create('news_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['rss', 'api', 'scrape'])->default('rss');
            $table->string('url');
            $table->string('logo')->nullable();
            $table->string('language', 5)->default('en');
            $table->boolean('requires_translation')->default(false);
            $table->string('translate_to', 5)->nullable();
            $table->unsignedTinyInteger('priority')->default(50); // 1-100
            $table->unsignedInteger('fetch_interval')->default(900); // seconds
            $table->boolean('is_active')->default(true);
            $table->json('config')->nullable(); // Source-specific config
            $table->timestamp('last_fetched_at')->nullable();
            $table->timestamps();
        });

        Schema::create('raw_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_source_id')->constrained()->cascadeOnDelete();
            $table->string('external_id')->nullable();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('url')->nullable();
            $table->string('author')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('external_published_at')->nullable();
            $table->enum('status', ['pending', 'processed', 'rejected', 'published'])->default('pending');
            $table->decimal('relevance_score', 4, 2)->nullable();
            $table->foreignId('news_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->unique(['news_source_id', 'external_id']);
        });

        Schema::create('content_ideas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['race', 'preview', 'review', 'transfer', 'analysis', 'feature', 'breaking'])->default('race');
            $table->enum('status', ['pending', 'approved', 'in_progress', 'published', 'rejected'])->default('pending');
            $table->decimal('score', 5, 2)->nullable();
            $table->json('context')->nullable(); // RAG context data
            $table->foreignId('race_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('news_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_ideas');
        Schema::dropIfExists('raw_contents');
        Schema::dropIfExists('news_sources');
    }
};
