<?php

namespace App\Console\Commands;

use App\Services\F1DataService;
use Illuminate\Console\Command;

class SyncF1Data extends Command
{
    protected $signature = 'f1:sync-data 
                            {--season= : Specific season to sync (default: current year)}
                            {--type= : Type of data to sync: all, teams, drivers, circuits, races, standings, results}
                            {--force : Force sync even if recently updated}';

    protected $description = 'Synchronize F1 data from external APIs (Ergast, OpenF1)';

    public function handle(F1DataService $f1Service): int
    {
        $season = $this->option('season') ?? now()->year;
        $type = $this->option('type') ?? 'all';
        $force = $this->option('force');

        $this->info("Starting F1 data synchronization for {$season} season...");
        $this->newLine();

        $types = $type === 'all' 
            ? ['teams', 'drivers', 'circuits', 'races', 'standings', 'results']
            : [$type];

        foreach ($types as $dataType) {
            $this->syncDataType($f1Service, $dataType, $season);
        }

        $this->newLine();
        $this->info('✓ F1 data synchronization completed!');

        return Command::SUCCESS;
    }

    protected function syncDataType(F1DataService $f1Service, string $type, int $season): void
    {
        $this->info("Syncing {$type}...");
        
        $progressBar = $this->output->createProgressBar(100);
        $progressBar->start();

        try {
            $result = match ($type) {
                'teams' => $f1Service->syncTeams($season),
                'drivers' => $f1Service->syncDrivers($season),
                'circuits' => $f1Service->syncCircuits(),
                'races' => $f1Service->syncRaces($season),
                'standings' => $this->syncStandings($f1Service, $season),
                'results' => $f1Service->syncResults($season),
                default => throw new \InvalidArgumentException("Unknown data type: {$type}"),
            };

            $progressBar->finish();
            $this->newLine();

            if (is_array($result)) {
                $count = count($result);
                $this->line("  <fg=green>✓</> Synced {$count} {$type}");
            } else {
                $this->line("  <fg=green>✓</> {$type} sync completed");
            }

        } catch (\Exception $e) {
            $progressBar->finish();
            $this->newLine();
            $this->error("  ✗ Failed to sync {$type}: " . $e->getMessage());
        }
    }

    protected function syncStandings(F1DataService $f1Service, int $season): array
    {
        $drivers = $f1Service->syncDriverStandings($season);
        $constructors = $f1Service->syncConstructorStandings($season);
        
        return array_merge($drivers ?? [], $constructors ?? []);
    }
}
