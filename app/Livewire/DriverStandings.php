<?php

namespace App\Livewire;

use App\Models\DriverStanding;
use Livewire\Component;

class DriverStandings extends Component
{
    public int $limit = 20;
    public ?int $season = null;

    public function mount(?int $season = null, int $limit = 20): void
    {
        $this->season = $season ?? now()->year;
        $this->limit = $limit;
    }

    public function getStandingsProperty()
    {
        return DriverStanding::getCurrentStandings()->take($this->limit);
    }

    public function render()
    {
        return view('livewire.driver-standings', [
            'standings' => $this->standings,
        ]);
    }
}
