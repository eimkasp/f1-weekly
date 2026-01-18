<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class RaceController extends Controller
{
    public function index(): View
    {
        $season = request('season', now()->year);
        
        $races = Race::with('circuit')
            ->where('season', $season)
            ->orderBy('round')
            ->get();

        return view('pages.races.index', [
            'races' => $races,
            'season' => $season,
        ]);
    }

    public function show(Race $race): View
    {
        $race->load([
            'circuit',
            'sessions',
            'results' => function ($query) {
                $query->with('driver.team')->orderBy('position');
            },
            'news' => function ($query) {
                $query->published()->latest('published_at')->limit(5);
            },
        ]);

        return view('pages.races.show', [
            'race' => $race,
        ]);
    }

    public function live(): View
    {
        $race = Race::live()
            ->with(['circuit', 'sessions', 'results.driver.team'])
            ->first();

        if (!$race) {
            // Get next upcoming race
            $race = Race::upcoming()
                ->with(['circuit', 'sessions'])
                ->orderBy('race_date')
                ->first();
        }

        return view('pages.races.live', [
            'race' => $race,
            'isLive' => $race?->isLive() ?? false,
        ]);
    }

    public function liveTiming(Request $request)
    {
        try {
            // Fetch live timing from OpenF1
            $positions = Http::timeout(5)
                ->get('https://api.openf1.org/v1/position', [
                    'session_key' => 'latest',
                ])
                ->json();

            $session = Http::timeout(5)
                ->get('https://api.openf1.org/v1/sessions', [
                    'session_key' => 'latest',
                ])
                ->json();

            return response()->json([
                'positions' => collect($positions)
                    ->groupBy('driver_number')
                    ->map(fn ($p) => $p->last())
                    ->sortBy('position')
                    ->values(),
                'session' => $session[0] ?? null,
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to fetch live timing',
                'message' => $e->getMessage(),
            ], 503);
        }
    }
}
