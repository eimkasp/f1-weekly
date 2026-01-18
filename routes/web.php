<?php

use App\Http\Controllers\CalendarExportController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [PageController::class, 'home'])->name('home');

// News
Route::prefix('news')->name('news.')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('index');
    Route::get('/category/{category}', [NewsController::class, 'category'])->name('category');
    Route::get('/{news:slug}', [NewsController::class, 'show'])->name('show');
});

// Drivers
Route::prefix('drivers')->name('drivers.')->group(function () {
    Route::get('/', [DriverController::class, 'index'])->name('index');
    Route::get('/{driver:slug}', [DriverController::class, 'show'])->name('show');
});
Route::get('/driver/{driver:slug}', [DriverController::class, 'show'])->name('driver.show');

// Teams
Route::prefix('teams')->name('teams.')->group(function () {
    Route::get('/', [TeamController::class, 'index'])->name('index');
    Route::get('/{team:slug}', [TeamController::class, 'show'])->name('show');
});
Route::get('/team/{team:slug}', [TeamController::class, 'show'])->name('team.show');

// Races
Route::prefix('races')->name('races.')->group(function () {
    Route::get('/', [RaceController::class, 'index'])->name('index');
    Route::get('/live', [RaceController::class, 'live'])->name('live');
    Route::get('/{race:slug}', [RaceController::class, 'show'])->name('show');
});
Route::get('/race/{race:slug}', [RaceController::class, 'show'])->name('race.show');
Route::get('/race/live', [RaceController::class, 'live'])->name('race.live');

// Calendar & Standings
Route::get('/calendar', [PageController::class, 'calendar'])->name('calendar');
Route::get('/standings', [PageController::class, 'standings'])->name('standings');
Route::get('/standings/constructors', [PageController::class, 'constructorStandings'])->name('standings.constructors');

// Prediction Markets / Odds
Route::get('/odds', [PageController::class, 'odds'])->name('odds');

// Calendar Export
Route::prefix('calendar/export')->name('calendar.export.')->group(function () {
    Route::get('/', [CalendarExportController::class, 'exportOptions'])->name('options');
    Route::get('/full.ics', [CalendarExportController::class, 'downloadFullCalendar'])->name('full');
    Route::get('/race/{race:slug}.ics', [CalendarExportController::class, 'downloadRaceCalendar'])->name('race');
    Route::get('/google/{race:slug}', [CalendarExportController::class, 'googleCalendarLink'])->name('google');
    Route::get('/outlook/{race:slug}', [CalendarExportController::class, 'outlookCalendarLink'])->name('outlook');
});

// Sitemap & Feeds
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');
Route::get('/feed/rss', [NewsController::class, 'rssFeed'])->name('feed.rss');
Route::get('/feed/atom', [NewsController::class, 'atomFeed'])->name('feed.atom');
