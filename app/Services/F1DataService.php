<?php

namespace App\Services;

use App\Models\Driver;
use App\Models\Team;
use App\Models\Circuit;
use App\Models\Race;
use App\Models\RaceResult;
use App\Models\DriverStanding;
use App\Models\ConstructorStanding;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class F1DataService
{
    // Jolpica API (Ergast successor) - Ergast deprecated in 2024
    protected string $ergastUrl = 'https://api.jolpi.ca/ergast/f1';
    protected string $openF1Url = 'https://api.openf1.org/v1';

    /**
     * Sync all current season data
     */
    public function syncAll(int $season = null): array
    {
        $season = $season ?? now()->year;
        
        $results = [
            'teams' => $this->syncTeams($season),
            'drivers' => $this->syncDrivers($season),
            'circuits' => $this->syncCircuits(),
            'races' => $this->syncRaces($season),
            'standings' => $this->syncStandings($season),
        ];

        Log::channel('f1-data')->info('Full data sync completed', $results);

        return $results;
    }

    /**
     * Sync teams from Ergast API
     */
    public function syncTeams(int $season = null): int
    {
        $season = $season ?? now()->year;
        $cacheKey = "f1_teams_{$season}";

        $data = Cache::remember($cacheKey, 3600, function () use ($season) {
            $response = Http::get("{$this->ergastUrl}/{$season}/constructors.json");
            return $response->json('MRData.ConstructorTable.Constructors', []);
        });

        $count = 0;
        foreach ($data as $constructor) {
            Team::updateOrCreate(
                ['slug' => $constructor['constructorId']],
                [
                    'name' => $constructor['name'],
                    'short_name' => $constructor['name'],
                    'is_active' => true,
                ]
            );
            $count++;
        }

        return $count;
    }

    /**
     * Sync drivers from Ergast API
     */
    public function syncDrivers(int $season = null): int
    {
        $season = $season ?? now()->year;
        $cacheKey = "f1_drivers_{$season}";

        $data = Cache::remember($cacheKey, 3600, function () use ($season) {
            $response = Http::get("{$this->ergastUrl}/{$season}/drivers.json");
            return $response->json('MRData.DriverTable.Drivers', []);
        });

        $count = 0;
        foreach ($data as $driver) {
            $dob = isset($driver['dateOfBirth']) ? Carbon::parse($driver['dateOfBirth']) : null;
            
            Driver::updateOrCreate(
                ['slug' => $driver['driverId']],
                [
                    'first_name' => $driver['givenName'],
                    'last_name' => $driver['familyName'],
                    'code' => strtoupper(substr($driver['familyName'], 0, 3)),
                    'number' => $driver['permanentNumber'] ?? null,
                    'nationality' => $driver['nationality'] ?? null,
                    'date_of_birth' => $dob,
                    'is_active' => true,
                ]
            );
            $count++;
        }

        return $count;
    }

    /**
     * Sync circuits from Ergast API
     */
    public function syncCircuits(): int
    {
        $cacheKey = 'f1_circuits';

        $data = Cache::remember($cacheKey, 86400, function () {
            // Using robust fetcher
            $json = $this->fetchJson("{$this->ergastUrl}/circuits.json", ['limit' => 100]);
            return data_get($json, 'MRData.CircuitTable.Circuits', []);
        });

        $count = 0;
        foreach ($data as $circuit) {
            Circuit::updateOrCreate(
                ['slug' => $circuit['circuitId']],
                [
                    'name' => $circuit['circuitName'],
                    'city' => $circuit['Location']['locality'] ?? null,
                    'country' => $circuit['Location']['country'] ?? 'Unknown',
                    'latitude' => $circuit['Location']['lat'] ?? null,
                    'longitude' => $circuit['Location']['long'] ?? null,
                ]
            );
            $count++;
        }

        return $count;
    }

    /**
     * Sync race schedule from Ergast API
     */
    public function syncRaces(int $season = null): int
    {
        $season = $season ?? now()->year;
        $cacheKey = "f1_races_{$season}";

        $data = Cache::remember($cacheKey, 3600, function () use ($season) {
            $response = Http::get("{$this->ergastUrl}/{$season}.json");
            return $response->json('MRData.RaceTable.Races', []);
        });

        $count = 0;
        foreach ($data as $race) {
            $circuit = Circuit::firstOrCreate(
                ['slug' => $race['Circuit']['circuitId']],
                [
                    'name' => $race['Circuit']['circuitName'],
                    'city' => $race['Circuit']['Location']['locality'] ?? null,
                    'country' => $race['Circuit']['Location']['country'] ?? 'Unknown',
                    'latitude' => $race['Circuit']['Location']['lat'] ?? null,
                    'longitude' => $race['Circuit']['Location']['long'] ?? null,
                ]
            );

            $raceDate = Carbon::parse($race['date']);
            if (isset($race['time'])) {
                $time = Carbon::parse($race['time']);
                $raceDate->setTime($time->hour, $time->minute, $time->second);
            }

            $status = 'scheduled';
            if ($raceDate->isPast()) {
                $status = 'completed';
            }

            Race::updateOrCreate(
                ['season' => $season, 'round' => $race['round']],
                [
                    'circuit_id' => $circuit->id,
                    'name' => $race['raceName'],
                    'slug' => $race['round'] . '-' . \Str::slug($race['raceName']) . '-' . $season,
                    'race_date' => $raceDate,
                    'status' => $status,
                    'is_sprint_weekend' => isset($race['Sprint']),
                ]
            );
            $count++;
        }

        return $count;
    }

    /**
     * Sync driver standings
     */
    public function syncStandings(int $season = null): array
    {
        $season = $season ?? now()->year;

        return [
            'drivers' => $this->syncDriverStandings($season),
            'constructors' => $this->syncConstructorStandings($season),
        ];
    }

    /**
     * Sync driver championship standings
     */
    public function syncDriverStandings(int $season): int
    {
        $cacheKey = "f1_driver_standings_{$season}";

        $data = Cache::remember($cacheKey, 1800, function () use ($season) {
            $response = Http::get("{$this->ergastUrl}/{$season}/driverStandings.json");
            return $response->json('MRData.StandingsTable.StandingsLists.0', []);
        });

        if (empty($data)) {
            return 0;
        }

        $round = (int) ($data['round'] ?? 0);
        $standings = $data['DriverStandings'] ?? [];

        $count = 0;
        foreach ($standings as $standing) {
            $driver = Driver::where('slug', $standing['Driver']['driverId'])->first();
            $team = Team::where('slug', $standing['Constructors'][0]['constructorId'] ?? '')->first();

            if (!$driver) {
                continue;
            }

            DriverStanding::updateOrCreate(
                [
                    'season' => $season,
                    'round' => $round,
                    'driver_id' => $driver->id,
                ],
                [
                    'team_id' => $team?->id,
                    'position' => $standing['position'],
                    'points' => $standing['points'],
                    'wins' => $standing['wins'],
                ]
            );
            $count++;
        }

        return $count;
    }

    /**
     * Sync constructor championship standings
     */
    public function syncConstructorStandings(int $season): int
    {
        $cacheKey = "f1_constructor_standings_{$season}";

        $data = Cache::remember($cacheKey, 1800, function () use ($season) {
            $response = Http::get("{$this->ergastUrl}/{$season}/constructorStandings.json");
            return $response->json('MRData.StandingsTable.StandingsLists.0', []);
        });

        if (empty($data)) {
            return 0;
        }

        $round = (int) ($data['round'] ?? 0);
        $standings = $data['ConstructorStandings'] ?? [];

        $count = 0;
        foreach ($standings as $standing) {
            $team = Team::where('slug', $standing['Constructor']['constructorId'])->first();

            if (!$team) {
                continue;
            }

            ConstructorStanding::updateOrCreate(
                [
                    'season' => $season,
                    'round' => $round,
                    'team_id' => $team->id,
                ],
                [
                    'position' => $standing['position'],
                    'points' => $standing['points'],
                    'wins' => $standing['wins'],
                ]
            );
            $count++;
        }

        return $count;
    }

    /**
     * Sync race results for a specific race
     */
    public function syncRaceResults(int $season, int $round): int
    {
        $response = Http::get("{$this->ergastUrl}/{$season}/{$round}/results.json");
        $data = $response->json('MRData.RaceTable.Races.0.Results', []);

        $race = Race::where('season', $season)->where('round', $round)->first();
        
        if (!$race || empty($data)) {
            return 0;
        }

        $count = 0;
        foreach ($data as $result) {
            $driver = Driver::where('slug', $result['Driver']['driverId'])->first();
            $team = Team::where('slug', $result['Constructor']['constructorId'])->first();

            if (!$driver || !$team) {
                continue;
            }

            $status = 'finished';
            if (isset($result['status'])) {
                if (str_contains($result['status'], 'Lap')) {
                    $status = $result['status'];
                } elseif ($result['status'] !== 'Finished') {
                    $status = 'DNF';
                }
            }

            RaceResult::updateOrCreate(
                [
                    'race_id' => $race->id,
                    'driver_id' => $driver->id,
                ],
                [
                    'team_id' => $team->id,
                    'position' => $result['position'],
                    'grid_position' => $result['grid'],
                    'points' => $result['points'],
                    'laps_completed' => $result['laps'],
                    'time' => $result['Time']['time'] ?? null,
                    'fastest_lap' => $result['FastestLap']['Time']['time'] ?? null,
                    'fastest_lap_number' => $result['FastestLap']['lap'] ?? null,
                    'has_fastest_lap' => ($result['FastestLap']['rank'] ?? 0) == 1,
                    'status' => $status,
                    'status_detail' => $result['status'] ?? null,
                ]
            );
            $count++;
        }

        // Update race status
        $race->update(['status' => 'completed']);

        return $count;
    }

    /**
     * Get next upcoming race
     */
    public function getNextRace(): ?Race
    {
        return Race::upcoming()
            ->with('circuit')
            ->first();
    }

    /**
     * Get last completed race
     */
    public function getLastRace(): ?Race
    {
        return Race::completed()
            ->with(['circuit', 'results.driver', 'results.team'])
            ->first();
    }

    /**
     * Robust fetcher with retry and error handling
     */
    protected function fetchJson(string $url, array $params = []): array
    {
        try {
            $response = Http::retry(3, 300)
                ->timeout(15)
                ->get($url, $params);

            if ($response->successful()) {
                return $response->json() ?? [];
            }

            Log::channel('f1-data')->warning("API Error: {$response->status()} for {$url}");
            return [];
        } catch (\Exception $e) {
            Log::channel('f1-data')->error("Connection Error: {$e->getMessage()} for {$url}");
            return [];
        }
    }
}
