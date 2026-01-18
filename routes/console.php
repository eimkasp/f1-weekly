<?php

use App\Jobs\FetchF1DataJob;
use App\Jobs\ProcessContentPipelineJob;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| Scheduled tasks for the autonomous F1 news platform
|
*/

// Sync F1 data every 30 minutes during race weekends, hourly otherwise
Schedule::job(new FetchF1DataJob(types: ['races', 'standings']))
    ->hourly()
    ->name('f1:sync-hourly')
    ->withoutOverlapping();

// Full data sync daily at 6 AM
Schedule::job(new FetchF1DataJob(types: ['teams', 'drivers', 'circuits', 'races', 'standings', 'results']))
    ->dailyAt('06:00')
    ->name('f1:sync-daily')
    ->withoutOverlapping();

// Generate content ideas daily at 7 AM
Schedule::command('ai:suggest-topics --limit=5 --auto-approve')
    ->dailyAt('07:00')
    ->name('ai:suggest-topics')
    ->withoutOverlapping();

// Process content pipeline 3 times daily
Schedule::job(new ProcessContentPipelineJob(maxArticles: 3))
    ->twiceDaily(8, 16)
    ->name('content:pipeline')
    ->withoutOverlapping();

// Additional news generation at noon
Schedule::command('ai:generate-news --limit=2')
    ->dailyAt('12:00')
    ->name('ai:generate-noon')
    ->withoutOverlapping();

// Cleanup old content ideas weekly
Schedule::command('model:prune', ['--model' => 'App\Models\ContentIdea'])
    ->weekly()
    ->name('content:prune');
