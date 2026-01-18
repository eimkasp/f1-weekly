<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DriverStanding;
use App\Models\ConstructorStanding;
use App\Services\F1DataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StandingsController extends Controller
{
    public function __construct(
        protected F1DataService $f1DataService
    ) {}

    /**
     * Get current driver standings
     * 
     * curl http://localhost:8000/api/standings/drivers
     */
    public function drivers(Request $request): JsonResponse
    {
        $standings = DriverStanding::getCurrentStandings();

        if ($standings->isEmpty()) {
            // Try to sync from API
            $season = now()->year;
            $synced = $this->f1DataService->syncDriverStandings($season);
            
            if ($synced > 0) {
                $standings = DriverStanding::getCurrentStandings();
            }
        }

        return response()->json([
            'success' => true,
            'season' => $standings->first()?->season ?? now()->year,
            'round' => $standings->first()?->round ?? 0,
            'count' => $standings->count(),
            'standings' => $standings->map(fn($s) => [
                'position' => $s->position,
                'driver' => [
                    'id' => $s->driver_id,
                    'name' => $s->driver?->name,
                    'number' => $s->driver?->number,
                    'nationality' => $s->driver?->nationality,
                ],
                'team' => [
                    'id' => $s->team_id,
                    'name' => $s->team?->name,
                ],
                'points' => $s->points,
                'wins' => $s->wins,
            ]),
        ]);
    }

    /**
     * Get current constructor standings
     * 
     * curl http://localhost:8000/api/standings/constructors
     */
    public function constructors(Request $request): JsonResponse
    {
        $season = now()->year;
        
        $standings = ConstructorStanding::with('team')
            ->where('season', $season)
            ->orderBy('position')
            ->get();

        // Get latest round
        $latestRound = $standings->max('round');
        $standings = $standings->where('round', $latestRound);

        if ($standings->isEmpty()) {
            // Try to sync from API
            $synced = $this->f1DataService->syncConstructorStandings($season);
            
            if ($synced > 0) {
                $standings = ConstructorStanding::with('team')
                    ->where('season', $season)
                    ->orderBy('position')
                    ->get();
                $latestRound = $standings->max('round');
                $standings = $standings->where('round', $latestRound);
            }
        }

        return response()->json([
            'success' => true,
            'season' => $season,
            'round' => $latestRound ?? 0,
            'count' => $standings->count(),
            'standings' => $standings->values()->map(fn($s) => [
                'position' => $s->position,
                'team' => [
                    'id' => $s->team_id,
                    'name' => $s->team?->name,
                    'country' => $s->team?->country,
                ],
                'points' => $s->points,
                'wins' => $s->wins,
            ]),
        ]);
    }

    /**
     * Get driver standings for specific season
     * 
     * curl http://localhost:8000/api/standings/drivers/2023
     */
    public function driversBySeason(int $season): JsonResponse
    {
        $standings = DriverStanding::with(['driver', 'team'])
            ->where('season', $season)
            ->orderBy('position')
            ->get();

        // Get latest round
        $latestRound = $standings->max('round');
        $standings = $standings->where('round', $latestRound);

        if ($standings->isEmpty()) {
            // Try to sync from API
            $synced = $this->f1DataService->syncDriverStandings($season);
            
            if ($synced > 0) {
                $standings = DriverStanding::with(['driver', 'team'])
                    ->where('season', $season)
                    ->orderBy('position')
                    ->get();
                $latestRound = $standings->max('round');
                $standings = $standings->where('round', $latestRound);
            }
        }

        return response()->json([
            'success' => true,
            'season' => $season,
            'round' => $latestRound ?? 0,
            'count' => $standings->count(),
            'standings' => $standings->values()->map(fn($s) => [
                'position' => $s->position,
                'driver' => [
                    'id' => $s->driver_id,
                    'name' => $s->driver?->name,
                    'number' => $s->driver?->number,
                    'nationality' => $s->driver?->nationality,
                ],
                'team' => [
                    'id' => $s->team_id,
                    'name' => $s->team?->name,
                ],
                'points' => $s->points,
                'wins' => $s->wins,
            ]),
        ]);
    }

    /**
     * Get constructor standings for specific season
     * 
     * curl http://localhost:8000/api/standings/constructors/2023
     */
    public function constructorsBySeason(int $season): JsonResponse
    {
        $standings = ConstructorStanding::with('team')
            ->where('season', $season)
            ->orderBy('position')
            ->get();

        // Get latest round
        $latestRound = $standings->max('round');
        $standings = $standings->where('round', $latestRound);

        if ($standings->isEmpty()) {
            // Try to sync from API
            $synced = $this->f1DataService->syncConstructorStandings($season);
            
            if ($synced > 0) {
                $standings = ConstructorStanding::with('team')
                    ->where('season', $season)
                    ->orderBy('position')
                    ->get();
                $latestRound = $standings->max('round');
                $standings = $standings->where('round', $latestRound);
            }
        }

        return response()->json([
            'success' => true,
            'season' => $season,
            'round' => $latestRound ?? 0,
            'count' => $standings->count(),
            'standings' => $standings->values()->map(fn($s) => [
                'position' => $s->position,
                'team' => [
                    'id' => $s->team_id,
                    'name' => $s->team?->name,
                    'country' => $s->team?->country,
                ],
                'points' => $s->points,
                'wins' => $s->wins,
            ]),
        ]);
    }
}
