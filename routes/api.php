<?php

use App\Http\Controllers\Api\F1DataController;
use App\Http\Controllers\Api\ArticleGeneratorController;
use App\Http\Controllers\Api\StandingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are accessible via curl commands or API clients.
| Base URL: /api
|
*/

// Health check
Route::get('/health', fn() => response()->json(['status' => 'ok', 'timestamp' => now()]));

// ============================================================================
// F1 Data Sync Endpoints
// ============================================================================
Route::prefix('sync')->group(function () {
    // Sync all F1 data
    Route::post('/all', [F1DataController::class, 'syncAll']);
    
    // Sync specific data types
    Route::post('/teams', [F1DataController::class, 'syncTeams']);
    Route::post('/drivers', [F1DataController::class, 'syncDrivers']);
    Route::post('/circuits', [F1DataController::class, 'syncCircuits']);
    Route::post('/races', [F1DataController::class, 'syncRaces']);
    Route::post('/standings', [F1DataController::class, 'syncStandings']);
    Route::post('/results/{season}/{round}', [F1DataController::class, 'syncRaceResults']);
});

// ============================================================================
// Standings Endpoints (Read-only)
// ============================================================================
Route::prefix('standings')->group(function () {
    Route::get('/drivers', [StandingsController::class, 'drivers']);
    Route::get('/constructors', [StandingsController::class, 'constructors']);
    Route::get('/drivers/{season}', [StandingsController::class, 'driversBySeason']);
    Route::get('/constructors/{season}', [StandingsController::class, 'constructorsBySeason']);
});

// ============================================================================
// Article Generation Endpoints
// ============================================================================
Route::prefix('articles')->group(function () {
    // Generate article from title/topic
    Route::post('/generate', [ArticleGeneratorController::class, 'generate']);
    
    // Generate from content idea
    Route::post('/generate-from-idea/{ideaId}', [ArticleGeneratorController::class, 'generateFromIdea']);
    
    // Suggest new content topics
    Route::post('/suggest-topics', [ArticleGeneratorController::class, 'suggestTopics']);
    
    // List generated articles
    Route::get('/', [ArticleGeneratorController::class, 'index']);
    
    // Get single article
    Route::get('/{id}', [ArticleGeneratorController::class, 'show']);
    
    // Update article status (publish/unpublish)
    Route::patch('/{id}/status', [ArticleGeneratorController::class, 'updateStatus']);
});

// ============================================================================
// Content Ideas Endpoints
// ============================================================================
Route::prefix('ideas')->group(function () {
    Route::get('/', [ArticleGeneratorController::class, 'listIdeas']);
    Route::post('/', [ArticleGeneratorController::class, 'createIdea']);
    Route::get('/{id}', [ArticleGeneratorController::class, 'showIdea']);
    Route::patch('/{id}/approve', [ArticleGeneratorController::class, 'approveIdea']);
    Route::patch('/{id}/reject', [ArticleGeneratorController::class, 'rejectIdea']);
});

// ============================================================================
// Races Endpoints
// ============================================================================
Route::prefix('races')->group(function () {
    Route::get('/', [F1DataController::class, 'races']);
    Route::get('/next', [F1DataController::class, 'nextRace']);
    Route::get('/last', [F1DataController::class, 'lastRace']);
    Route::get('/{season}', [F1DataController::class, 'racesBySeason']);
    Route::get('/{season}/{round}', [F1DataController::class, 'raceDetails']);
});

// ============================================================================
// Drivers & Teams
// ============================================================================
Route::get('/drivers', [F1DataController::class, 'drivers']);
Route::get('/drivers/{id}', [F1DataController::class, 'driver']);
Route::get('/teams', [F1DataController::class, 'teams']);
Route::get('/teams/{id}', [F1DataController::class, 'team']);
