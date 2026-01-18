<?php

namespace App\Jobs;

use App\Models\ContentIdea;
use App\Services\AINewsGeneratorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessContentPipelineJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 1800; // 30 minutes

    public function __construct(
        public int $maxArticles = 5,
        public bool $autoPublish = false
    ) {}

    public function handle(AINewsGeneratorService $aiService): void
    {
        Log::info("ProcessContentPipelineJob: Starting autonomous content pipeline", [
            'max_articles' => $this->maxArticles,
            'auto_publish' => $this->autoPublish,
        ]);

        // Step 1: Generate new content ideas if needed
        $pendingIdeas = ContentIdea::where('status', 'approved')->count();
        
        if ($pendingIdeas < $this->maxArticles) {
            Log::info("ProcessContentPipelineJob: Generating new content ideas");
            $this->generateNewIdeas($aiService, $this->maxArticles - $pendingIdeas);
        }

        // Step 2: Process approved ideas into articles
        $ideas = ContentIdea::where('status', 'approved')
            ->orderByDesc('priority')
            ->orderBy('created_at')
            ->limit($this->maxArticles)
            ->get();

        Log::info("ProcessContentPipelineJob: Processing {$ideas->count()} content ideas");

        $generated = 0;
        $failed = 0;

        foreach ($ideas as $idea) {
            try {
                // Dispatch individual article generation
                WriteArticleJob::dispatch($idea);
                $generated++;

                // Rate limiting - wait between dispatches
                sleep(5);

            } catch (\Exception $e) {
                Log::error("ProcessContentPipelineJob: Failed to dispatch article job", [
                    'idea_id' => $idea->id,
                    'error' => $e->getMessage(),
                ]);
                $failed++;
            }
        }

        Log::info("ProcessContentPipelineJob: Pipeline completed", [
            'dispatched' => $generated,
            'failed' => $failed,
        ]);
    }

    protected function generateNewIdeas(AINewsGeneratorService $aiService, int $count): void
    {
        try {
            $ideas = $aiService->suggestContentIdeas([
                'limit' => $count,
                'auto_approve' => true,
            ]);

            Log::info("ProcessContentPipelineJob: Generated new content ideas", [
                'count' => count($ideas),
            ]);

        } catch (\Exception $e) {
            Log::error("ProcessContentPipelineJob: Failed to generate ideas", [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessContentPipelineJob: Pipeline job failed', [
            'error' => $exception->getMessage(),
        ]);
    }
}
