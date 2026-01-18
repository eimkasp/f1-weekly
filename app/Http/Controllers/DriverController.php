<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\View\View;

class DriverController extends Controller
{
    public function index(): View
    {
        $drivers = Driver::with(['team', 'standings' => function ($query) {
                $query->where('season', now()->year)->orderBy('position');
            }])
            ->whereHas('team')
            ->orderBy('number')
            ->get();

        return view('pages.drivers.index', [
            'drivers' => $drivers,
        ]);
    }

    public function show(Driver $driver): View
    {
        $driver->load([
            'team',
            'standings' => function ($query) {
                $query->orderByDesc('season')->limit(5);
            },
            'raceResults' => function ($query) {
                $query->with('race.circuit')
                    ->orderByDesc('race_id')
                    ->limit(10);
            },
            'news' => function ($query) {
                $query->published()
                    ->latest('published_at')
                    ->limit(5);
            },
        ]);

        // Career stats
        $stats = [
            'championships' => $driver->championships ?? 0,
            'race_wins' => $driver->race_wins ?? 0,
            'podiums' => $driver->podiums ?? 0,
            'pole_positions' => $driver->pole_positions ?? 0,
            'fastest_laps' => $driver->fastest_laps ?? 0,
            'career_points' => $driver->career_points ?? 0,
        ];

        // Recent form (last 5 races)
        $recentForm = $driver->raceResults
            ->take(5)
            ->map(fn ($result) => $result->position ?? 'DNF');

        return view('pages.drivers.show', [
            'driver' => $driver,
            'stats' => $stats,
            'recentForm' => $recentForm,
        ]);
    }
}
