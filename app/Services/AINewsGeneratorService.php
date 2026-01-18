<?php

namespace App\Services;

use App\Models\News;
use App\Models\ContentIdea;
use App\Models\Driver;
use App\Models\Team;
use App\Models\Race;
use App\Models\DriverStanding;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;

class AINewsGeneratorService
{
    protected F1DataService $f1DataService;

    public function __construct(F1DataService $f1DataService)
    {
        $this->f1DataService = $f1DataService;
    }

    /**
     * Generate a news article from a content idea
     */
    public function generateFromIdea(ContentIdea $idea): ?News
    {
        $idea->markInProgress();

        try {
            $context = $this->buildRAGContext($idea);
            $content = $this->generateArticleContent($idea, $context);
            
            if (!$content) {
                Log::channel('ai-news')->error('Failed to generate content for idea', ['idea_id' => $idea->id]);
                return null;
            }

            $news = $this->createNewsArticle($idea, $content, $context);
            
            if ($news) {
                $idea->markPublished($news);
                $this->generateSEO($news);
            }

            return $news;
        } catch (\Exception $e) {
            Log::channel('ai-news')->error('News generation failed', [
                'idea_id' => $idea->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Generate article directly from title/topic
     */
    public function generateFromTitle(string $title, string $category = 'race'): ?News
    {
        $idea = ContentIdea::create([
            'title' => $title,
            'type' => $category,
            'status' => ContentIdea::STATUS_APPROVED,
        ]);

        return $this->generateFromIdea($idea);
    }

    /**
     * Build RAG context for AI generation
     */
    protected function buildRAGContext(ContentIdea $idea): array
    {
        $context = [
            'type' => $idea->type,
            'title' => $idea->title,
            'description' => $idea->description,
        ];

        // Add driver context
        if ($idea->driver_id) {
            $driver = $idea->driver()->with('team')->first();
            $context['driver'] = $this->getDriverContext($driver);
        }

        // Add team context
        if ($idea->team_id) {
            $team = $idea->team()->with('drivers')->first();
            $context['team'] = $this->getTeamContext($team);
        }

        // Add race context
        if ($idea->race_id) {
            $race = $idea->race()->with(['circuit', 'results.driver'])->first();
            $context['race'] = $this->getRaceContext($race);
        }

        // Always add current standings
        $context['driver_standings'] = $this->getCurrentStandingsContext();
        $context['next_race'] = $this->getNextRaceContext();

        return $context;
    }

    /**
     * Get driver context for RAG
     */
    protected function getDriverContext(?Driver $driver): array
    {
        if (!$driver) {
            return [];
        }

        $standing = $driver->current_standing;
        $recentResults = $driver->getRecentResults(5);

        return [
            'name' => $driver->name,
            'team' => $driver->team?->name,
            'number' => $driver->number,
            'nationality' => $driver->nationality,
            'championships' => $driver->world_championships,
            'career_wins' => $driver->race_wins,
            'current_position' => $standing?->position,
            'current_points' => $standing?->points,
            'recent_results' => $recentResults->map(fn($r) => [
                'race' => $r->race?->name,
                'position' => $r->position,
                'points' => $r->points,
            ])->toArray(),
        ];
    }

    /**
     * Get team context for RAG
     */
    protected function getTeamContext(?Team $team): array
    {
        if (!$team) {
            return [];
        }

        return [
            'name' => $team->name,
            'base' => $team->base,
            'principal' => $team->team_principal,
            'championships' => $team->world_championships,
            'drivers' => $team->drivers->pluck('name')->toArray(),
        ];
    }

    /**
     * Get race context for RAG
     */
    protected function getRaceContext(?Race $race): array
    {
        if (!$race) {
            return [];
        }

        return [
            'name' => $race->name,
            'circuit' => $race->circuit?->name,
            'country' => $race->circuit?->country,
            'date' => $race->date?->format('F j, Y'),
            'laps' => $race->laps,
            'status' => $race->status,
            'winner' => $race->winner?->driver?->name,
            'podium' => $race->podium?->map(fn($r) => $r->driver?->name)->toArray(),
        ];
    }

    /**
     * Get current standings context
     */
    protected function getCurrentStandingsContext(): array
    {
        $standings = DriverStanding::getCurrentStandings();

        return $standings->take(10)->map(fn($s) => [
            'position' => $s->position,
            'driver' => $s->driver?->name,
            'team' => $s->team?->name,
            'points' => $s->points,
        ])->toArray();
    }

    /**
     * Get next race context
     */
    protected function getNextRaceContext(): array
    {
        $race = $this->f1DataService->getNextRace();

        if (!$race) {
            return [];
        }

        return [
            'name' => $race->name,
            'circuit' => $race->circuit?->name,
            'country' => $race->circuit?->country,
            'date' => $race->date?->format('F j, Y'),
            'countdown' => $race->countdown,
        ];
    }

    /**
     * Generate article content using AI
     */
    protected function generateArticleContent(ContentIdea $idea, array $context): ?array
    {
        $systemPrompt = $this->getSystemPrompt();
        $userPrompt = $this->getUserPrompt($idea, $context);

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4-turbo-preview',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'temperature' => 0.7,
                'max_tokens' => 2000,
                'response_format' => ['type' => 'json_object'],
            ]);

            $content = json_decode($response->choices[0]->message->content, true);

            Log::channel('ai-news')->info('AI content generated', [
                'idea_id' => $idea->id,
                'tokens' => $response->usage->totalTokens,
            ]);

            return $content;
        } catch (\Exception $e) {
            Log::channel('ai-news')->error('OpenAI request failed', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get system prompt for AI
     */
    protected function getSystemPrompt(): string
    {
        return <<<PROMPT
You are a professional Formula 1 journalist writing for F1 Weekly, a leading motorsport news platform.

Your writing style:
- Professional yet engaging motorsport journalism
- Factual and accurate - never invent statistics or quotes
- Include relevant statistics and data points
- Use technical F1 terminology appropriately
- Write for knowledgeable F1 fans but remain accessible
- Maintain objectivity while being engaging

Output Requirements:
Return a JSON object with these fields:
- title: Compelling headline (60-80 characters)
- excerpt: Brief summary (150-200 characters)
- content: Full article in HTML format (500-800 words)
- summary: One paragraph summary
- tags: Array of relevant tags (max 5)
- sentiment: "positive", "neutral", or "negative"
PROMPT;
    }

    /**
     * Build user prompt with context
     */
    protected function getUserPrompt(ContentIdea $idea, array $context): string
    {
        $contextJson = json_encode($context, JSON_PRETTY_PRINT);

        return <<<PROMPT
Write a {$idea->type} article about: {$idea->title}

Additional context:
{$idea->description}

DATA CONTEXT (use this for accurate information):
{$contextJson}

Requirements:
- Use ONLY the data provided in the context above
- Do not invent statistics, quotes, or facts
- If the context lacks information for a claim, omit that claim
- Write in a professional motorsport journalism style
- Include relevant statistics from the provided data
- Article should be 500-800 words

Return a properly formatted JSON object.
PROMPT;
    }

    /**
     * Create news article from AI content
     */
    protected function createNewsArticle(ContentIdea $idea, array $content, array $context): ?News
    {
        try {
            $news = News::create([
                'title' => $content['title'] ?? $idea->title,
                'slug' => Str::slug($content['title'] ?? $idea->title),
                'excerpt' => $content['excerpt'] ?? null,
                'content' => $content['content'] ?? '',
                'summary' => $content['summary'] ?? null,
                'category' => $idea->type,
                'status' => 'pending_review',
                'ai_generated' => true,
                'ai_model' => 'gpt-4-turbo-preview',
                'sentiment_label' => $content['sentiment'] ?? 'neutral',
            ]);

            // Sync tags
            if (!empty($content['tags'])) {
                $news->syncTags($content['tags']);
            }

            // Attach related entities
            if ($idea->driver_id) {
                $news->drivers()->attach($idea->driver_id);
            }
            if ($idea->team_id) {
                $news->teams()->attach($idea->team_id);
            }
            if ($idea->race_id) {
                $news->races()->attach($idea->race_id);
            }

            Log::channel('ai-news')->info('News article created', [
                'news_id' => $news->id,
                'idea_id' => $idea->id,
            ]);

            return $news;
        } catch (\Exception $e) {
            Log::channel('ai-news')->error('Failed to create news article', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Generate SEO metadata for article
     */
    protected function generateSEO(News $news): void
    {
        $news->update([
            'seo_title' => Str::limit($news->title, 60),
            'seo_description' => $news->excerpt ?? Str::limit(strip_tags($news->content), 160),
            'structured_data' => $news->generateStructuredData(),
        ]);
    }
}
