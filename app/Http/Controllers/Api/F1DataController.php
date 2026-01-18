<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Team;
use App\Models\Race;
use App\Services\F1DataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class F1DataController extends Controller
{
    public function __construct(
        protected F1DataService $f1DataService
    ) {}

    /**
     * Sync all F1 data
     * 
     * curl -X POST http://localhost:8000/api/sync/all
     */
    public function syncAll(Request $request): JsonResponse
    {
        $season = $request->input('season', now()->year);
        
        try {
            // Clear cache for fresh data
            if ($request->boolean('force')) {
                Cache::forget("f1_teams_{$season}");
                Cache::forget("f1_drivers_{$season}");
                Cache::forget('f1_circuits');
                Cache::forget("f1_races_{$season}");
                Cache::forget("f1_driver_standings_{$season}");
                Cache::forget("f1_constructor_standings_{$season}");
            }

            $results = $this->f1DataService->syncAll($season);

            return response()->json([
                'success' => true,
                'message' => 'F1 data synchronized successfully',
                'season' => $season,
                'results' => $results,
                'synced_at' => now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync teams
     * 
     * curl -X POST http://localhost:8000/api/sync/teams
     */
    public function syncTeams(Request $request): JsonResponse
    {
        $season = $request->input('season', now()->year);

        try {
            if ($request->boolean('force')) {
                Cache::forget("f1_teams_{$season}");
            }

            $count = $this->f1DataService->syncTeams($season);

            return response()->json([
                'success' => true,
                'message' => "Synced {$count} teams",
                'count' => $count,
                'season' => $season,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync drivers
     * 
     * curl -X POST http://localhost:8000/api/sync/drivers
     */
    public function syncDrivers(Request $request): JsonResponse
    {
        $season = $request->input('season', now()->year);

        try {
            if ($request->boolean('force')) {
                Cache::forget("f1_drivers_{$season}");
            }

            $count = $this->f1DataService->syncDrivers($season);

            return response()->json([
                'success' => true,
                'message' => "Synced {$count} drivers",
                'count' => $count,
                'season' => $season,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync circuits
     * 
     * curl -X POST http://localhost:8000/api/sync/circuits
     */
    public function syncCircuits(Request $request): JsonResponse
    {
        try {
            if ($request->boolean('force')) {
                Cache::forget('f1_circuits');
            }

            $count = $this->f1DataService->syncCircuits();

            return response()->json([
                'success' => true,
                'message' => "Synced {$count} circuits",
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync races
     * 
     * curl -X POST http://localhost:8000/api/sync/races?season=2024
     */
    public function syncRaces(Request $request): JsonResponse
    {
        $season = $request->input('season', now()->year);

        try {
            if ($request->boolean('force')) {
                Cache::forget("f1_races_{$season}");
            }

            $count = $this->f1DataService->syncRaces($season);

            return response()->json([
                'success' => true,
                'message' => "Synced {$count} races for {$season}",
                'count' => $count,
                'season' => $season,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync standings
     * 
     * curl -X POST http://localhost:8000/api/sync/standings?season=2024
     */
    public function syncStandings(Request $request): JsonResponse
    {
        $season = $request->input('season', now()->year);

        try {
            if ($request->boolean('force')) {
                Cache::forget("f1_driver_standings_{$season}");
                Cache::forget("f1_constructor_standings_{$season}");
            }

            $results = $this->f1DataService->syncStandings($season);

            return response()->json([
                'success' => true,
                'message' => 'Standings synchronized',
                'season' => $season,
                'drivers_synced' => $results['drivers'] ?? 0,
                'constructors_synced' => $results['constructors'] ?? 0,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync race results
     * 
     * curl -X POST http://localhost:8000/api/sync/results/2024/1
     */
    public function syncRaceResults(int $season, int $round): JsonResponse
    {
        try {
            $count = $this->f1DataService->syncRaceResults($season, $round);

            return response()->json([
                'success' => true,
                'message' => "Synced {$count} results for round {$round}",
                'count' => $count,
                'season' => $season,
                'round' => $round,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all races
     */
    public function races(Request $request): JsonResponse
    {
        $season = $request->input('season', now()->year);
        
        $races = Race::with('circuit')
            ->where('season', $season)
            ->orderBy('round')
            ->get();

        return response()->json([
            'success' => true,
            'season' => $season,
            'count' => $races->count(),
            'races' => $races,
        ]);
    }

    /**
     * Get races by season
     */
    public function racesBySeason(int $season): JsonResponse
    {
        $races = Race::with('circuit')
            ->where('season', $season)
            ->orderBy('round')
            ->get();

        return response()->json([
            'success' => true,
            'season' => $season,
            'count' => $races->count(),
            'races' => $races,
        ]);
    }

    /**
     * Get next race
     */
    public function nextRace(): JsonResponse
    {
        $race = $this->f1DataService->getNextRace();

        if (!$race) {
            return response()->json([
                'success' => false,
                'message' => 'No upcoming races found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'race' => $race->load(['circuit', 'sessions']),
        ]);
    }

    /**
     * Get last race
     */
    public function lastRace(): JsonResponse
    {
        $race = $this->f1DataService->getLastRace();

        if (!$race) {
            return response()->json([
                'success' => false,
                'message' => 'No completed races found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'race' => $race,
        ]);
    }

    /**
     * Get race details
     */
    public function raceDetails(int $season, int $round): JsonResponse
    {
        $race = Race::with(['circuit', 'results.driver', 'results.team', 'sessions'])
            ->where('season', $season)
            ->where('round', $round)
            ->first();

        if (!$race) {
            return response()->json([
                'success' => false,
                'message' => 'Race not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'race' => $race,
        ]);
    }

    /**
     * Get all drivers
     */
    public function drivers(): JsonResponse
    {
        $drivers = Driver::with('team')
            ->where('is_active', true)
            ->orderBy('number')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $drivers->count(),
            'drivers' => $drivers,
        ]);
    }

    /**
     * Get single driver
     */
    public function driver(int $id): JsonResponse
    {
        $driver = Driver::with(['team', 'standings' => fn($q) => $q->latest('season')->latest('round')->limit(1)])
            ->find($id);

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'driver' => $driver,
        ]);
    }

    /**
     * Get all teams
     */
    public function teams(): JsonResponse
    {
        $teams = Team::with('drivers')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $teams->count(),
            'teams' => $teams,
        ]);
    }

    /**
     * Get single team
     */
    public function team(int $id): JsonResponse
    {
        $team = Team::with(['drivers', 'standings' => fn($q) => $q->latest('season')->latest('round')->limit(1)])
            ->find($id);

        if (!$team) {
            return response()->json([
                'success' => false,
                'message' => 'Team not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'team' => $team,
        ]);
    }
}
