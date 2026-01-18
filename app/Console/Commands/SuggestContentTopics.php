<?php

namespace App\Console\Commands;

use App\Models\ContentIdea;
use App\Models\Driver;
use App\Models\Race;
use App\Models\Team;
use App\Services\AINewsGeneratorService;
use Illuminate\Console\Command;

class SuggestContentTopics extends Command
{
    protected $signature = 'ai:suggest-topics 
                            {--limit=10 : Number of topics to suggest}
                            {--type= : Focus on specific content type}
                            {--auto-approve : Automatically approve suggestions}';

    protected $description = 'Use AI to suggest new content topics based on current F1 events';

    public function handle(AINewsGeneratorService $aiService): int
    {
        $limit = (int) $this->option('limit');
        $type = $this->option('type');
        $autoApprove = $this->option('auto-approve');

        $this->info('🎯 AI Content Topic Suggester');
        $this->newLine();

        // Gather current F1 context
        $context = $this->gatherContext();
        
        $this->info("Context gathered:");
        $this->line("  • Next race: " . ($context['next_race']?->name ?? 'None'));
        $this->line("  • Recent races: " . count($context['recent_races']));
        $this->line("  • Top drivers: " . $context['standings']->count());
        $this->newLine();

        $this->info("Generating {$limit} content suggestions...");
        $this->newLine();

        $suggestions = $this->generateSuggestions($aiService, $context, $limit, $type);

        if (empty($suggestions)) {
            $this->warn('No suggestions generated. Check AI service configuration.');
            return Command::FAILURE;
        }

        // Display suggestions
        $tableData = [];
        foreach ($suggestions as $i => $suggestion) {
            $tableData[] = [
                $i + 1,
                $suggestion['type'],
                \Str::limit($suggestion['title'], 50),
                $suggestion['priority'],
            ];
        }

        $this->table(['#', 'Type', 'Title', 'Priority'], $tableData);
        $this->newLine();

        // Save suggestions
        if ($autoApprove || $this->confirm('Save these suggestions as content ideas?', true)) {
            $saved = $this->saveSuggestions($suggestions, $autoApprove);
            $this->info("✓ Saved {$saved} content ideas" . ($autoApprove ? ' (auto-approved)' : ''));
        }

        return Command::SUCCESS;
    }

    protected function gatherContext(): array
    {
        return [
            'next_race' => Race::upcoming()
                ->with('circuit')
                ->orderBy('race_date')
                ->first(),
            'recent_races' => Race::completed()
                ->with(['circuit', 'results.driver'])
                ->orderByDesc('race_date')
                ->limit(3)
                ->get(),
            'standings' => \App\Models\DriverStanding::getCurrentStandings()->take(10),
            'teams' => Team::with('drivers')->get(),
        ];
    }

    protected function generateSuggestions(
        AINewsGeneratorService $aiService,
        array $context,
        int $limit,
        ?string $type
    ): array {
        $suggestions = [];

        // Next race preview
        if ($context['next_race'] && (!$type || $type === 'preview')) {
            $suggestions[] = [
                'type' => 'preview',
                'title' => "{$context['next_race']->name} Preview: What to Expect",
                'description' => "Comprehensive preview of the upcoming {$context['next_race']->name} at {$context['next_race']->circuit->name}",
                'priority' => 'high',
                'race_id' => $context['next_race']->id,
                'keywords' => ['preview', $context['next_race']->circuit->country ?? '', 'F1'],
            ];
        }

        // Recent race reviews
        foreach ($context['recent_races'] as $race) {
            if ($type && $type !== 'race_report') continue;
            if (count($suggestions) >= $limit) break;

            $winner = $race->results->where('position', 1)->first()?->driver;
            $suggestions[] = [
                'type' => 'race_report',
                'title' => "{$race->name}: " . ($winner ? "{$winner->full_name} Triumphs" : "Race Analysis"),
                'description' => "Detailed analysis of the {$race->name}",
                'priority' => 'high',
                'race_id' => $race->id,
                'keywords' => ['race report', $race->circuit->country ?? '', 'F1', $winner?->last_name ?? ''],
            ];
        }

        // Championship battle
        if ($context['standings']->count() >= 2 && (!$type || $type === 'driver_profile')) {
            $leader = $context['standings']->first();
            $second = $context['standings']->skip(1)->first();
            
            if ($leader && $second) {
                $gap = $leader->points - $second->points;
                $suggestions[] = [
                    'type' => 'driver_profile',
                    'title' => "Championship Battle: {$leader->driver->last_name} vs {$second->driver->last_name}",
                    'description' => "Analysis of the title fight with {$gap} points separating the top two",
                    'priority' => 'high',
                    'driver_id' => $leader->driver_id,
                    'keywords' => ['championship', $leader->driver->last_name, $second->driver->last_name, 'title fight'],
                ];
            }
        }

        // Team updates
        foreach ($context['teams']->take(5) as $team) {
            if ($type && $type !== 'team_update') continue;
            if (count($suggestions) >= $limit) break;

            $suggestions[] = [
                'type' => 'team_update',
                'title' => "{$team->name}: Season Progress Report",
                'description' => "How {$team->name} is performing in the current season",
                'priority' => 'medium',
                'team_id' => $team->id,
                'keywords' => [$team->name, 'team', 'F1', 'performance'],
            ];
        }

        // Technical analysis
        if (!$type || $type === 'technical') {
            $suggestions[] = [
                'type' => 'technical',
                'title' => 'Technical Deep Dive: Latest Aerodynamic Developments',
                'description' => 'Analysis of recent technical upgrades across the F1 grid',
                'priority' => 'medium',
                'keywords' => ['technical', 'aerodynamics', 'upgrades', 'F1'],
            ];
        }

        return array_slice($suggestions, 0, $limit);
    }

    protected function saveSuggestions(array $suggestions, bool $autoApprove): int
    {
        $saved = 0;

        foreach ($suggestions as $suggestion) {
            // Check for duplicates
            $exists = ContentIdea::where('title', $suggestion['title'])
                ->whereIn('status', ['pending', 'approved', 'in_progress'])
                ->exists();

            if ($exists) {
                continue;
            }

            ContentIdea::create([
                'title' => $suggestion['title'],
                'type' => $suggestion['type'],
                'description' => $suggestion['description'] ?? null,
                'priority' => $suggestion['priority'] ?? 'medium',
                'status' => $autoApprove ? 'approved' : 'pending',
                'driver_id' => $suggestion['driver_id'] ?? null,
                'team_id' => $suggestion['team_id'] ?? null,
                'race_id' => $suggestion['race_id'] ?? null,
                'keywords' => $suggestion['keywords'] ?? [],
                'suggested_at' => now(),
            ]);

            $saved++;
        }

        return $saved;
    }
}
