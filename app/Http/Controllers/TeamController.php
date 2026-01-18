<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        $teams = Team::with(['drivers', 'constructorStandings' => function ($query) {
                $query->where('season', now()->year)->orderBy('position');
            }])
            ->orderBy('name')
            ->get();

        return view('pages.teams.index', [
            'teams' => $teams,
        ]);
    }

    public function show(Team $team): View
    {
        $team->load([
            'drivers.standings' => function ($query) {
                $query->where('season', now()->year);
            },
            'constructorStandings' => function ($query) {
                $query->orderByDesc('season')->limit(5);
            },
            'raceResults' => function ($query) {
                $query->with(['race.circuit', 'driver'])
                    ->whereHas('race', function ($q) {
                        $q->where('season', now()->year);
                    })
                    ->orderByDesc('race_id')
                    ->limit(20);
            },
            'news' => function ($query) {
                $query->published()
                    ->latest('published_at')
                    ->limit(5);
            },
        ]);

        // Team stats for current season
        $currentStanding = $team->constructorStandings
            ->where('season', now()->year)
            ->first();

        $stats = [
            'position' => $currentStanding?->position ?? '-',
            'points' => $currentStanding?->points ?? 0,
            'wins' => $currentStanding?->wins ?? 0,
            'podiums' => $team->raceResults->where('position', '<=', 3)->count(),
        ];

        return view('pages.teams.show', [
            'team' => $team,
            'stats' => $stats,
        ]);
    }
}
