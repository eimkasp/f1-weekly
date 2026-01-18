<?php

namespace App\Console\Commands;

use App\Services\PolymarketService;
use Illuminate\Console\Command;

class SyncPolymarketMarkets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'polymarket:sync 
                            {--prices-only : Only update prices for existing markets}
                            {--force : Force sync even if recently synced}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync F1 prediction markets from Polymarket CLOB API';

    /**
     * Execute the console command.
     */
    public function handle(PolymarketService $service): int
    {
        $this->info('Starting Polymarket sync...');

        if ($this->option('prices-only')) {
            $this->info('Updating prices only...');
            $updated = $service->updateAllPrices();
            $this->info("Updated prices for {$updated} markets.");
            return Command::SUCCESS;
        }

        $this->info('Searching for F1 markets...');
        $synced = $service->syncF1Markets();
        
        $this->info("Synced {$synced} F1 markets from Polymarket.");
        
        return Command::SUCCESS;
    }
}
