<?php

namespace App\Livewire;

use App\Models\PolymarketMarket;
use App\Services\PolymarketService;
use Livewire\Component;

class PolymarketOdds extends Component
{
    public string $view = 'championship'; // championship, race, all
    public ?int $raceId = null;
    public bool $showAll = false;

    public function setView(string $view): void
    {
        $this->view = $view;
    }

    public function refreshPrices(): void
    {
        $service = app(PolymarketService::class);
        $service->updateAllPrices();
    }

    public function render()
    {
        $query = PolymarketMarket::active()
            ->f1Markets()
            ->with(['driver', 'race', 'team'])
            ->orderBy('volume', 'desc');

        if ($this->view === 'championship') {
            $query->whereIn('market_type', ['championship', 'constructor_championship']);
        } elseif ($this->view === 'race') {
            $query->where('market_type', 'race_winner');
            if ($this->raceId) {
                $query->where('race_id', $this->raceId);
            }
        }

        $markets = $this->showAll ? $query->get() : $query->limit(10)->get();

        $groupedMarkets = [
            'championship' => $markets->where('market_type', 'championship')->values(),
            'constructor' => $markets->where('market_type', 'constructor_championship')->values(),
            'race' => $markets->where('market_type', 'race_winner')->values(),
            'other' => $markets->whereNotIn('market_type', ['championship', 'constructor_championship', 'race_winner'])->values(),
        ];

        return view('livewire.polymarket-odds', [
            'markets' => $markets,
            'groupedMarkets' => $groupedMarkets,
        ]);
    }
}
