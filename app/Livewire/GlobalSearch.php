<?php

namespace App\Livewire;

use App\Models\Driver;
use App\Models\Race;
use App\Models\Team;
use Livewire\Component;

class GlobalSearch extends Component
{
    public $query = '';
    public $results = [];

    public function updatedQuery()
    {
        $this->results = [];

        if (strlen($this->query) < 2) {
            return;
        }

        $term = '%' . $this->query . '%';

        $this->results = [
            'drivers' => Driver::query()
                ->where('first_name', 'like', $term)
                ->orWhere('last_name', 'like', $term)
                ->take(5)
                ->get(),
            
            'teams' => Team::query()
                ->where('name', 'like', $term)
                ->take(5)
                ->get(),

            'races' => Race::query()
                ->where('name', 'like', $term)
                ->orderBy('race_date', 'desc')
                ->take(5)
                ->get(),
        ];
    }

    public function render()
    {
        return view('livewire.global-search');
    }
}
