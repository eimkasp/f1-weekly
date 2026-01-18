<?php

namespace App\Http\Controllers;

use App\Models\ConstructorStanding;
use App\Models\DriverStanding;
use App\Models\News;
use App\Models\Race;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $liveRace = Race::live()->first();
        
        return view('pages.home', [
            'liveRace' => $liveRace,
        ]);
    }

    public function calendar(): View
    {
        return view('pages.calendar');
    }

    public function standings(): View
    {
        return view('pages.standings');
    }

    public function games(): View
    {
        return view('pages.games');
    }

    public function constructorStandings(): View
    {
        $standings = ConstructorStanding::with('team')
            ->where('season', now()->year)
            ->orderBy('position')
            ->get();

        return view('pages.constructor-standings', [
            'standings' => $standings,
        ]);
    }

    public function odds(): View
    {
        return view('pages.odds');
    }

    public function sitemap()
    {
        $news = News::published()
            ->select(['slug', 'updated_at'])
            ->latest('updated_at')
            ->get();

        $races = Race::select(['slug', 'updated_at', 'season'])->get();
        
        $drivers = \App\Models\Driver::where('is_active', true)
            ->select(['slug', 'updated_at'])
            ->get();
        
        $teams = \App\Models\Team::where('is_active', true)
            ->select(['slug', 'updated_at'])
            ->get();

        return response()
            ->view('sitemap', compact('news', 'races', 'drivers', 'teams'))
            ->header('Content-Type', 'application/xml');
    }

    public function apiDriverStandings(Request $request)
    {
        $season = $request->get('season', now()->year);
        
        $standings = DriverStanding::getCurrentStandings($season);

        return response()->json([
            'data' => $standings,
            'season' => $season,
        ]);
    }

    public function apiConstructorStandings(Request $request)
    {
        $season = $request->get('season', now()->year);
        
        $standings = ConstructorStanding::with('team')
            ->where('season', $season)
            ->orderBy('position')
            ->get();

        return response()->json([
            'data' => $standings,
            'season' => $season,
        ]);
    }
}
