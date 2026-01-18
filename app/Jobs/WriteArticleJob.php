<?php

namespace App\Jobs;

use App\Models\ContentIdea;
use App\Models\News;
use App\Services\AINewsGeneratorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WriteArticleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $backoff = 120;
    public int $timeout = 600;

    public function __construct(
        public ContentIdea $contentIdea
    ) {}

    public function handle(AINewsGeneratorService $aiService): void
    {
        Log::info("WriteArticleJob: Starting article generation", [
            'idea_id' => $this->contentIdea->id,
            'title' => $this->contentIdea->title,
            'type' => $this->contentIdea->type,
        ]);

        // Mark idea as in progress
        $this->contentIdea->update(['status' => 'in_progress']);

        try {
            $article = $aiService->generateFromIdea($this->contentIdea);

            if ($article) {
                Log::info("WriteArticleJob: Article generated successfully", [
                    'idea_id' => $this->contentIdea->id,
                    'article_id' => $article->id,
                    'title' => $article->title,
                ]);

                // Mark idea as written
                $this->contentIdea->update([
                    'status' => 'written',
                    'news_id' => $article->id,
                ]);
            } else {
                throw new \Exception('AI service returned null article');
            }

        } catch (\Exception $e) {
            Log::error("WriteArticleJob: Failed to generate article", [
                'idea_id' => $this->contentIdea->id,
                'error' => $e->getMessage(),
            ]);

            $this->contentIdea->update([
                'status' => 'failed',
                'notes' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('WriteArticleJob: Job failed permanently', [
            'idea_id' => $this->contentIdea->id,
            'title' => $this->contentIdea->title,
            'error' => $exception->getMessage(),
        ]);

        $this->contentIdea->update([
            'status' => 'failed',
            'notes' => 'Job failed: ' . $exception->getMessage(),
        ]);
    }
}
