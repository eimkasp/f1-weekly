<?php

namespace App\Console\Commands;

use App\Models\ContentIdea;
use App\Models\News;
use App\Services\AINewsGeneratorService;
use Illuminate\Console\Command;

class GenerateAINews extends Command
{
    protected $signature = 'ai:generate-news 
                            {--type= : Type of news: race_report, driver_profile, team_update, technical, preview}
                            {--driver-id= : Generate news about specific driver}
                            {--team-id= : Generate news about specific team}
                            {--race-id= : Generate news about specific race}
                            {--idea-id= : Generate from specific content idea}
                            {--limit=5 : Maximum articles to generate}
                            {--dry-run : Preview without saving}';

    protected $description = 'Generate AI-powered F1 news articles';

    public function handle(AINewsGeneratorService $aiService): int
    {
        $type = $this->option('type');
        $driverId = $this->option('driver-id');
        $teamId = $this->option('team-id');
        $raceId = $this->option('race-id');
        $ideaId = $this->option('idea-id');
        $limit = (int) $this->option('limit');
        $dryRun = $this->option('dry-run');

        $this->info('🤖 AI News Generator');
        $this->newLine();

        if ($dryRun) {
            $this->warn('Running in DRY RUN mode - no articles will be saved');
            $this->newLine();
        }

        // Generate from specific content idea
        if ($ideaId) {
            return $this->generateFromIdea($aiService, $ideaId, $dryRun);
        }

        // Generate pending content ideas
        $ideas = $this->getPendingIdeas($type, $driverId, $teamId, $raceId, $limit);

        if ($ideas->isEmpty()) {
            $this->info('No pending content ideas found. Generating fresh ideas...');
            $this->generateFreshIdeas($aiService, $type, $driverId, $teamId, $raceId);
            $ideas = $this->getPendingIdeas($type, $driverId, $teamId, $raceId, $limit);
        }

        if ($ideas->isEmpty()) {
            $this->warn('No content ideas to generate. Use ai:suggest-topics first.');
            return Command::SUCCESS;
        }

        $this->info("Found {$ideas->count()} pending content ideas to process");
        $this->newLine();

        $generated = 0;
        $failed = 0;

        foreach ($ideas as $idea) {
            $this->line("Processing: {$idea->title}");

            try {
                if ($dryRun) {
                    $this->line("  <fg=yellow>[DRY RUN]</> Would generate article");
                    $generated++;
                    continue;
                }

                $article = $aiService->generateFromIdea($idea);

                if ($article) {
                    $this->line("  <fg=green>✓</> Generated: {$article->title}");
                    $generated++;
                } else {
                    $this->line("  <fg=red>✗</> Failed to generate article");
                    $failed++;
                }

            } catch (\Exception $e) {
                $this->error("  ✗ Error: " . $e->getMessage());
                $failed++;
                
                $idea->update([
                    'status' => 'failed',
                    'notes' => $e->getMessage(),
                ]);
            }

            // Small delay to avoid API rate limits
            sleep(2);
        }

        $this->newLine();
        $this->info("Generation complete: {$generated} articles created, {$failed} failed");

        return $failed > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    protected function generateFromIdea(AINewsGeneratorService $aiService, int $ideaId, bool $dryRun): int
    {
        $idea = ContentIdea::find($ideaId);

        if (!$idea) {
            $this->error("Content idea #{$ideaId} not found");
            return Command::FAILURE;
        }

        $this->info("Generating article from idea: {$idea->title}");

        if ($dryRun) {
            $this->info('[DRY RUN] Would generate article with context:');
            $this->table(['Key', 'Value'], [
                ['Type', $idea->type],
                ['Priority', $idea->priority],
                ['Status', $idea->status],
                ['Keywords', implode(', ', $idea->keywords ?? [])],
            ]);
            return Command::SUCCESS;
        }

        try {
            $article = $aiService->generateFromIdea($idea);
            
            $this->newLine();
            $this->info("✓ Article generated successfully!");
            $this->table(['Field', 'Value'], [
                ['ID', $article->id],
                ['Title', $article->title],
                ['Slug', $article->slug],
                ['Category', $article->category],
                ['Word Count', str_word_count($article->content)],
                ['Status', $article->status],
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Failed to generate article: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function getPendingIdeas(?string $type, ?int $driverId, ?int $teamId, ?int $raceId, int $limit)
    {
        $query = ContentIdea::where('status', 'approved')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc');

        if ($type) {
            $query->where('type', $type);
        }

        if ($driverId) {
            $query->where('driver_id', $driverId);
        }

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        if ($raceId) {
            $query->where('race_id', $raceId);
        }

        return $query->limit($limit)->get();
    }

    protected function generateFreshIdeas(
        AINewsGeneratorService $aiService,
        ?string $type,
        ?int $driverId,
        ?int $teamId,
        ?int $raceId
    ): void {
        $this->line('Analyzing current F1 landscape for content opportunities...');
        
        // Auto-approve generated ideas for immediate processing
        $ideas = $aiService->suggestContentIdeas([
            'type' => $type,
            'driver_id' => $driverId,
            'team_id' => $teamId,
            'race_id' => $raceId,
            'auto_approve' => true,
        ]);

        $this->line("Generated " . count($ideas) . " new content ideas");
    }
}
