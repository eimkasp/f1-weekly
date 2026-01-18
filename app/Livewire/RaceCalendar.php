<?php

namespace App\Livewire;

use App\Models\Race;
use Livewire\Component;

class RaceCalendar extends Component
{
    public ?int $season = null;
    public string $filter = 'all'; // all, upcoming, completed

    public function mount(?int $season = null): void
    {
        $this->season = $season ?? now()->year;
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    public function getRacesProperty()
    {
        $query = Race::with(['circuit'])
            ->where('season', $this->season)
            ->orderBy('round');

        return match ($this->filter) {
            'upcoming' => $query->upcoming()->get(),
            'completed' => $query->completed()->get(),
            default => $query->get(),
        };
    }

    public function getNextRaceProperty()
    {
        return Race::with(['circuit', 'sessions'])
            ->upcoming()
            ->orderBy('race_date')
            ->first();
    }

    public function render()
    {
        return view('livewire.race-calendar', [
            'races' => $this->races,
            'nextRace' => $this->nextRace,
        ]);
    }
}
