<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\ContentIdea;
use App\Services\AINewsGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleGeneratorController extends Controller
{
    public function __construct(
        protected AINewsGeneratorService $aiService
    ) {}

    /**
     * Generate article from title/topic
     * 
     * curl -X POST http://localhost:8000/api/articles/generate \
     *   -H "Content-Type: application/json" \
     *   -d '{"title": "Max Verstappen dominates Monaco GP qualifying", "category": "race"}'
     */
    public function generate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|in:race,driver,team,technical,preview,opinion',
            'driver_id' => 'nullable|integer|exists:drivers,id',
            'team_id' => 'nullable|integer|exists:teams,id',
            'race_id' => 'nullable|integer|exists:races,id',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Create content idea first
            $idea = ContentIdea::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'type' => $request->input('category', 'race'),
                'driver_id' => $request->input('driver_id'),
                'team_id' => $request->input('team_id'),
                'race_id' => $request->input('race_id'),
                'status' => ContentIdea::STATUS_APPROVED,
                'priority' => $request->input('priority', 'medium'),
            ]);

            // Generate article
            $article = $this->aiService->generateFromIdea($idea);

            if (!$article) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate article. Check OpenAI API key and quota.',
                    'idea_id' => $idea->id,
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Article generated successfully',
                'article' => [
                    'id' => $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'excerpt' => $article->excerpt,
                    'content' => $article->content,
                    'category' => $article->category,
                    'status' => $article->status,
                    'ai_generated' => $article->ai_generated,
                    'created_at' => $article->created_at->toIso8601String(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate article from content idea
     * 
     * curl -X POST http://localhost:8000/api/articles/generate-from-idea/1
     */
    public function generateFromIdea(int $ideaId): JsonResponse
    {
        $idea = ContentIdea::find($ideaId);

        if (!$idea) {
            return response()->json([
                'success' => false,
                'message' => 'Content idea not found',
            ], 404);
        }

        if ($idea->status === ContentIdea::STATUS_PUBLISHED) {
            return response()->json([
                'success' => false,
                'message' => 'This idea has already been published',
                'article_id' => $idea->news_id,
            ], 400);
        }

        try {
            $article = $this->aiService->generateFromIdea($idea);

            if (!$article) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate article',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Article generated successfully',
                'article' => [
                    'id' => $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'excerpt' => $article->excerpt,
                    'category' => $article->category,
                    'status' => $article->status,
                    'created_at' => $article->created_at->toIso8601String(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Suggest new content topics
     * 
     * curl -X POST http://localhost:8000/api/articles/suggest-topics \
     *   -H "Content-Type: application/json" \
     *   -d '{"count": 5}'
     */
    public function suggestTopics(Request $request): JsonResponse
    {
        $count = $request->input('count', 5);
        $type = $request->input('type');

        try {
            $ideas = $this->generateTopicIdeas($count, $type);

            return response()->json([
                'success' => true,
                'count' => count($ideas),
                'ideas' => $ideas,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate topic ideas using AI or templates
     */
    protected function generateTopicIdeas(int $count, ?string $type): array
    {
        $templates = [
            'race' => [
                '{driver} secures pole position at {circuit}',
                'Race preview: Everything you need to know about {race}',
                '{team} brings major upgrades for {race}',
                'Weather forecast could shake up {race}',
                'Pit strategy analysis for {race}',
            ],
            'driver' => [
                "{driver}'s journey to Formula 1",
                "Inside {driver}'s training regime",
                "{driver} vs rivals: Season performance comparison",
                "What makes {driver}'s driving style unique",
                "{driver} sets sights on championship glory",
            ],
            'team' => [
                "{team}'s technical evolution in {season}",
                "Behind the scenes at {team}",
                "{team} driver lineup: Strengths and challenges",
                "{team}'s championship aspirations for {season}",
                "Factory tour: Inside {team}'s headquarters",
            ],
            'technical' => [
                "Explained: The new aerodynamic regulations",
                "Power unit analysis: Who has the edge?",
                "DRS zones and their impact on racing",
                "Tire strategy deep dive",
                "Car development race: Mid-season update",
            ],
        ];

        $types = $type ? [$type] : array_keys($templates);
        $ideas = [];

        $drivers = \App\Models\Driver::where('is_active', true)->pluck('first_name', 'id')->toArray();
        $teams = \App\Models\Team::where('is_active', true)->pluck('name', 'id')->toArray();
        $nextRace = \App\Models\Race::upcoming()->with('circuit')->first();

        for ($i = 0; $i < $count; $i++) {
            $selectedType = $types[array_rand($types)];
            $template = $templates[$selectedType][array_rand($templates[$selectedType])];

            // Replace placeholders
            $title = str_replace(
                ['{driver}', '{team}', '{circuit}', '{race}', '{season}'],
                [
                    $drivers ? array_values($drivers)[array_rand($drivers)] : 'Driver',
                    $teams ? array_values($teams)[array_rand($teams)] : 'Team',
                    $nextRace?->circuit?->name ?? 'Circuit',
                    $nextRace?->name ?? 'Grand Prix',
                    now()->year,
                ],
                $template
            );

            $idea = ContentIdea::create([
                'title' => $title,
                'type' => $selectedType,
                'status' => ContentIdea::STATUS_DRAFT,
                'priority' => ['low', 'medium', 'high'][array_rand(['low', 'medium', 'high'])],
            ]);

            $ideas[] = [
                'id' => $idea->id,
                'title' => $idea->title,
                'type' => $idea->type,
                'status' => $idea->status,
            ];
        }

        return $ideas;
    }

    /**
     * List all articles
     * 
     * curl http://localhost:8000/api/articles
     */
    public function index(Request $request): JsonResponse
    {
        $query = News::query();

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->boolean('ai_only')) {
            $query->where('ai_generated', true);
        }

        $articles = $query
            ->latest()
            ->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'articles' => $articles,
        ]);
    }

    /**
     * Get single article
     * 
     * curl http://localhost:8000/api/articles/1
     */
    public function show(int $id): JsonResponse
    {
        $article = News::with(['drivers', 'teams', 'races', 'tags'])->find($id);

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'article' => $article,
        ]);
    }

    /**
     * Update article status
     * 
     * curl -X PATCH http://localhost:8000/api/articles/1/status \
     *   -H "Content-Type: application/json" \
     *   -d '{"status": "published"}'
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:draft,pending_review,published,archived',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $article = News::find($id);

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found',
            ], 404);
        }

        $oldStatus = $article->status;
        $article->update([
            'status' => $request->input('status'),
            'published_at' => $request->input('status') === 'published' ? now() : $article->published_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Article status updated from {$oldStatus} to {$article->status}",
            'article' => [
                'id' => $article->id,
                'title' => $article->title,
                'status' => $article->status,
            ],
        ]);
    }

    /**
     * List content ideas
     * 
     * curl http://localhost:8000/api/ideas
     */
    public function listIdeas(Request $request): JsonResponse
    {
        $query = ContentIdea::query();

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        $ideas = $query
            ->latest()
            ->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'ideas' => $ideas,
        ]);
    }

    /**
     * Create content idea
     * 
     * curl -X POST http://localhost:8000/api/ideas \
     *   -H "Content-Type: application/json" \
     *   -d '{"title": "Verstappen wins Monaco GP", "type": "race"}'
     */
    public function createIdea(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|in:race,driver,team,technical,preview,opinion',
            'description' => 'nullable|string',
            'driver_id' => 'nullable|integer|exists:drivers,id',
            'team_id' => 'nullable|integer|exists:teams,id',
            'race_id' => 'nullable|integer|exists:races,id',
            'priority' => 'nullable|string|in:low,medium,high,urgent',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $idea = ContentIdea::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'type' => $request->input('type', 'race'),
            'driver_id' => $request->input('driver_id'),
            'team_id' => $request->input('team_id'),
            'race_id' => $request->input('race_id'),
            'priority' => $request->input('priority', 'medium'),
            'status' => ContentIdea::STATUS_DRAFT,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Content idea created',
            'idea' => $idea,
        ], 201);
    }

    /**
     * Get single content idea
     */
    public function showIdea(int $id): JsonResponse
    {
        $idea = ContentIdea::with(['driver', 'team', 'race', 'news'])->find($id);

        if (!$idea) {
            return response()->json([
                'success' => false,
                'message' => 'Content idea not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'idea' => $idea,
        ]);
    }

    /**
     * Approve content idea
     * 
     * curl -X PATCH http://localhost:8000/api/ideas/1/approve
     */
    public function approveIdea(int $id): JsonResponse
    {
        $idea = ContentIdea::find($id);

        if (!$idea) {
            return response()->json([
                'success' => false,
                'message' => 'Content idea not found',
            ], 404);
        }

        $idea->update(['status' => ContentIdea::STATUS_APPROVED]);

        return response()->json([
            'success' => true,
            'message' => 'Content idea approved',
            'idea' => [
                'id' => $idea->id,
                'title' => $idea->title,
                'status' => $idea->status,
            ],
        ]);
    }

    /**
     * Reject content idea
     * 
     * curl -X PATCH http://localhost:8000/api/ideas/1/reject
     */
    public function rejectIdea(int $id): JsonResponse
    {
        $idea = ContentIdea::find($id);

        if (!$idea) {
            return response()->json([
                'success' => false,
                'message' => 'Content idea not found',
            ], 404);
        }

        $idea->update(['status' => ContentIdea::STATUS_REJECTED]);

        return response()->json([
            'success' => true,
            'message' => 'Content idea rejected',
            'idea' => [
                'id' => $idea->id,
                'title' => $idea->title,
                'status' => $idea->status,
            ],
        ]);
    }
}
