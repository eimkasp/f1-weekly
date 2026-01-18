<?php

namespace App\Jobs;

use App\Services\F1DataService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchF1DataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;
    public int $timeout = 300;

    public function __construct(
        public ?int $season = null,
        public array $types = ['races', 'standings', 'results']
    ) {
        $this->season = $season ?? now()->year;
    }

    public function handle(F1DataService $f1Service): void
    {
        Log::info("FetchF1DataJob: Starting sync for {$this->season} season", [
            'types' => $this->types,
        ]);

        foreach ($this->types as $type) {
            try {
                match ($type) {
                    'teams' => $f1Service->syncTeams($this->season),
                    'drivers' => $f1Service->syncDrivers($this->season),
                    'circuits' => $f1Service->syncCircuits(),
                    'races' => $f1Service->syncRaces($this->season),
                    'standings' => $this->syncStandings($f1Service),
                    'results' => $f1Service->syncResults($this->season),
                    default => Log::warning("Unknown sync type: {$type}"),
                };

                Log::info("FetchF1DataJob: Synced {$type} successfully");

            } catch (\Exception $e) {
                Log::error("FetchF1DataJob: Failed to sync {$type}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        Log::info("FetchF1DataJob: Completed sync for {$this->season} season");
    }

    protected function syncStandings(F1DataService $f1Service): void
    {
        $f1Service->syncDriverStandings($this->season);
        $f1Service->syncConstructorStandings($this->season);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('FetchF1DataJob: Job failed after all retries', [
            'season' => $this->season,
            'types' => $this->types,
            'error' => $exception->getMessage(),
        ]);
    }
}
