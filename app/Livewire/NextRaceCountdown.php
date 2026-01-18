<?php

namespace App\Livewire;

use App\Models\Race;
use Carbon\Carbon;
use Livewire\Component;

class NextRaceCountdown extends Component
{
    public $race;
    public $timeRemaining = [];

    public function mount()
    {
        $this->race = Race::where('race_date', '>=', Carbon::now())
            ->where('status', 'scheduled')
            ->orderBy('race_date', 'asc')
            ->first();

        // Fallback for demo purposes if no scheduled race is future
        if (!$this->race) {
            $this->race = Race::orderBy('race_date', 'desc')->first();
        }
    }

    public function calculateTimeRemaining()
    {
        if (!$this->race || !$this->race->race_date) return;

        $now = Carbon::now();
        $raceDate = Carbon::parse($this->race->race_date);
        
        if ($now->gt($raceDate)) {
            $this->timeRemaining = ['days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 0];
            return;
        }

        $diff = $now->diff($raceDate);
        $this->timeRemaining = [
            'days' => $diff->days, // Total days
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s,
        ];
    }

    public function render()
    {
        $this->calculateTimeRemaining();
        return view('livewire.next-race-countdown');
    }
}
