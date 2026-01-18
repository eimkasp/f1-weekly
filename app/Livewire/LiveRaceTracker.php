<?php

namespace App\Livewire;

use App\Models\Race;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class LiveRaceTracker extends Component
{
    public ?Race $race = null;
    public array $positions = [];
    public array $lapTimes = [];
    public ?int $currentLap = null;
    public ?int $totalLaps = null;
    public bool $isLive = false;
    public ?string $sessionType = null; // race, qualifying, practice
    public ?string $raceStatus = null; // scheduled, green, yellow, red, finished

    public function mount(?int $raceId = null): void
    {
        if ($raceId) {
            $this->race = Race::with(['circuit', 'results.driver.team'])->find($raceId);
        } else {
            $this->race = Race::live()->with(['circuit', 'results.driver.team'])->first();
        }

        if ($this->race) {
            $this->checkLiveStatus();
        }
    }

    public function checkLiveStatus(): void
    {
        if (!$this->race) {
            return;
        }

        $this->isLive = $this->race->isLive();
        
        if ($this->isLive) {
            $this->fetchLiveData();
        }
    }

    public function fetchLiveData(): void
    {
        if (!$this->isLive || !$this->race) {
            return;
        }

        try {
            // Fetch from OpenF1 API for live timing data
            $response = Http::timeout(5)->get('https://api.openf1.org/v1/position', [
                'session_key' => 'latest',
            ]);

            if ($response->successful()) {
                $this->positions = collect($response->json())
                    ->groupBy('driver_number')
                    ->map(fn($positions) => $positions->last())
                    ->sortBy('position')
                    ->values()
                    ->toArray();
            }

            // Fetch current lap
            $lapResponse = Http::timeout(5)->get('https://api.openf1.org/v1/laps', [
                'session_key' => 'latest',
                'driver_number' => 1, // Use lead driver
            ]);

            if ($lapResponse->successful()) {
                $laps = $lapResponse->json();
                if (!empty($laps)) {
                    $lastLap = end($laps);
                    $this->currentLap = $lastLap['lap_number'] ?? null;
                }
            }

            // Get session info
            $sessionResponse = Http::timeout(5)->get('https://api.openf1.org/v1/sessions', [
                'session_key' => 'latest',
            ]);

            if ($sessionResponse->successful()) {
                $sessions = $sessionResponse->json();
                if (!empty($sessions)) {
                    $session = end($sessions);
                    $this->sessionType = strtolower($session['session_name'] ?? 'race');
                    $this->totalLaps = $session['total_laps'] ?? null;
                }
            }

        } catch (\Exception $e) {
            // Silently fail for live data - show cached data instead
            \Log::warning('Failed to fetch live F1 data: ' . $e->getMessage());
        }
    }

    public function refreshData(): void
    {
        $this->checkLiveStatus();
        $this->fetchLiveData();
    }

    public function getDriverByNumber(int $number): ?array
    {
        // Map driver numbers to names (2024 season)
        $driverMap = [
            1 => ['name' => 'Max Verstappen', 'team' => 'Red Bull Racing'],
            11 => ['name' => 'Sergio Perez', 'team' => 'Red Bull Racing'],
            44 => ['name' => 'Lewis Hamilton', 'team' => 'Mercedes'],
            63 => ['name' => 'George Russell', 'team' => 'Mercedes'],
            16 => ['name' => 'Charles Leclerc', 'team' => 'Ferrari'],
            55 => ['name' => 'Carlos Sainz', 'team' => 'Ferrari'],
            4 => ['name' => 'Lando Norris', 'team' => 'McLaren'],
            81 => ['name' => 'Oscar Piastri', 'team' => 'McLaren'],
            14 => ['name' => 'Fernando Alonso', 'team' => 'Aston Martin'],
            18 => ['name' => 'Lance Stroll', 'team' => 'Aston Martin'],
            10 => ['name' => 'Pierre Gasly', 'team' => 'Alpine'],
            31 => ['name' => 'Esteban Ocon', 'team' => 'Alpine'],
            23 => ['name' => 'Alex Albon', 'team' => 'Williams'],
            2 => ['name' => 'Logan Sargeant', 'team' => 'Williams'],
            20 => ['name' => 'Kevin Magnussen', 'team' => 'Haas'],
            27 => ['name' => 'Nico Hulkenberg', 'team' => 'Haas'],
            77 => ['name' => 'Valtteri Bottas', 'team' => 'Sauber'],
            24 => ['name' => 'Zhou Guanyu', 'team' => 'Sauber'],
            22 => ['name' => 'Yuki Tsunoda', 'team' => 'RB'],
            3 => ['name' => 'Daniel Ricciardo', 'team' => 'RB'],
        ];

        return $driverMap[$number] ?? null;
    }

    public function render()
    {
        return view('livewire.live-race-tracker', [
            'race' => $this->race,
            'positions' => $this->positions,
            'isLive' => $this->isLive,
            'currentLap' => $this->currentLap,
            'totalLaps' => $this->totalLaps,
            'sessionType' => $this->sessionType,
        ]);
    }
}
