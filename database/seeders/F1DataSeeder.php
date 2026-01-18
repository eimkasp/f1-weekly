<?php

namespace Database\Seeders;

use App\Models\Circuit;
use App\Models\Driver;
use App\Models\DriverStanding;
use App\Models\Race;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class F1DataSeeder extends Seeder
{
    /**
     * Seed the database with initial F1 data.
     * Uses the existing JSON data files from the original Framework7 app.
     */
    public function run(): void
    {
        $this->seedTeams();
        $this->seedDrivers();
        $this->seedCircuits();
        $this->seedRaces();
        $this->seedStandings();
    }

    protected function seedTeams(): void
    {
        $teams = [
            [
                'name' => 'Red Bull Racing',
                'short_name' => 'Red Bull',
                'color' => '#3671C6',
                'base' => 'Milton Keynes, United Kingdom',
                'team_principal' => 'Christian Horner',
                'power_unit' => 'Red Bull Powertrains',
                'logo' => 'https://media.api-sports.io/formula-1/teams/1.png',
                'world_championships' => 6
            ],
            [
                'name' => 'Mercedes-AMG Petronas',
                'short_name' => 'Mercedes',
                'color' => '#27F4D2',
                'base' => 'Brackley, United Kingdom',
                'team_principal' => 'Toto Wolff',
                'power_unit' => 'Mercedes',
                'logo' => 'https://media.api-sports.io/formula-1/teams/5.png',
                'world_championships' => 8
            ],
            [
                'name' => 'Scuderia Ferrari',
                'short_name' => 'Ferrari',
                'color' => '#E8002D',
                'base' => 'Maranello, Italy',
                'team_principal' => 'Fred Vasseur',
                'power_unit' => 'Ferrari',
                'logo' => 'https://media.api-sports.io/formula-1/teams/3.png',
                'world_championships' => 16
            ],
            [
                'name' => 'McLaren F1 Team',
                'short_name' => 'McLaren',
                'color' => '#FF8000',
                'base' => 'Woking, United Kingdom',
                'team_principal' => 'Andrea Stella',
                'power_unit' => 'Mercedes',
                'logo' => 'https://media.api-sports.io/formula-1/teams/4.png',
                'world_championships' => 8
            ],
            [
                'name' => 'Aston Martin F1 Team',
                'short_name' => 'Aston Martin',
                'color' => '#229971',
                'base' => 'Silverstone, United Kingdom',
                'team_principal' => 'Mike Krack',
                'power_unit' => 'Mercedes',
                'logo' => 'https://media.api-sports.io/formula-1/teams/13.png',
                'world_championships' => 0
            ],
            [
                'name' => 'Alpine F1 Team',
                'short_name' => 'Alpine',
                'color' => '#FF87BC',
                'base' => 'Enstone, United Kingdom',
                'team_principal' => 'Bruno Famin',
                'power_unit' => 'Renault',
                'logo' => 'https://media.api-sports.io/formula-1/teams/14.png',
                'world_championships' => 2
            ],
            [
                'name' => 'Williams Racing',
                'short_name' => 'Williams',
                'color' => '#64C4FF',
                'base' => 'Grove, United Kingdom',
                'team_principal' => 'James Vowles',
                'power_unit' => 'Mercedes',
                'logo' => 'https://media.api-sports.io/formula-1/teams/7.png',
                'world_championships' => 9
            ],
            [
                'name' => 'Visa Cash App RB F1 Team',
                'short_name' => 'RB',
                'color' => '#6692FF',
                'base' => 'Faenza, Italy',
                'team_principal' => 'Laurent Mekies',
                'power_unit' => 'Red Bull Powertrains',
                'logo' => 'https://media.api-sports.io/formula-1/teams/11.png',
                'world_championships' => 0
            ],
            [
                'name' => 'Stake F1 Team Kick Sauber',
                'short_name' => 'Sauber',
                'color' => '#52E252',
                'base' => 'Hinwil, Switzerland',
                'team_principal' => 'Alessandro Alunni Bravi',
                'power_unit' => 'Ferrari',
                'logo' => 'https://media.api-sports.io/formula-1/teams/17.png',
                'world_championships' => 0
            ],
            [
                'name' => 'MoneyGram Haas F1 Team',
                'short_name' => 'Haas',
                'color' => '#B6BABD',
                'base' => 'Kannapolis, United States',
                'team_principal' => 'Ayao Komatsu',
                'power_unit' => 'Ferrari',
                'logo' => 'https://media.api-sports.io/formula-1/teams/16.png',
                'world_championships' => 0
            ],
        ];

        foreach ($teams as $team) {
            Team::updateOrCreate(
                ['name' => $team['name']],
                [
                    'short_name' => $team['short_name'],
                    'slug' => Str::slug($team['short_name']),
                    'color' => $team['color'],
                    'base' => $team['base'],
                    'team_principal' => $team['team_principal'],
                    'power_unit' => $team['power_unit'],
                    'logo' => $team['logo'],
                    'world_championships' => $team['world_championships'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Teams seeded successfully.');
    }

    protected function seedDrivers(): void
    {
        $drivers = [
            // Red Bull Racing
            ['first_name' => 'Max', 'last_name' => 'Verstappen', 'number' => 1, 'code' => 'VER', 'team' => 'Red Bull Racing', 'nationality' => 'Dutch', 'country_code' => 'nl', 'date_of_birth' => '1997-09-30', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/25.png', 'championships' => 4, 'race_wins' => 61, 'podiums' => 107, 'pole_positions' => 40],
            ['first_name' => 'Sergio', 'last_name' => 'Perez', 'number' => 11, 'code' => 'PER', 'team' => 'Red Bull Racing', 'nationality' => 'Mexican', 'country_code' => 'mx', 'date_of_birth' => '1990-01-26', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/17.png', 'championships' => 0, 'race_wins' => 6, 'podiums' => 39, 'pole_positions' => 3],
            
            // Mercedes
            ['first_name' => 'George', 'last_name' => 'Russell', 'number' => 63, 'code' => 'RUS', 'team' => 'Mercedes-AMG Petronas', 'nationality' => 'British', 'country_code' => 'gb', 'date_of_birth' => '1998-02-15', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/18.png', 'championships' => 0, 'race_wins' => 2, 'podiums' => 13, 'pole_positions' => 3],
            ['first_name' => 'Kimi', 'last_name' => 'Antonelli', 'number' => 12, 'code' => 'ANT', 'team' => 'Mercedes-AMG Petronas', 'nationality' => 'Italian', 'country_code' => 'it', 'date_of_birth' => '2006-08-25', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/12.png', 'championships' => 0, 'race_wins' => 0, 'podiums' => 0, 'pole_positions' => 0],
            
            // Ferrari
            ['first_name' => 'Charles', 'last_name' => 'Leclerc', 'number' => 16, 'code' => 'LEC', 'team' => 'Scuderia Ferrari', 'nationality' => 'Monegasque', 'country_code' => 'mc', 'date_of_birth' => '1997-10-16', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/34.png', 'championships' => 0, 'race_wins' => 7, 'podiums' => 37, 'pole_positions' => 25],
            ['first_name' => 'Lewis', 'last_name' => 'Hamilton', 'number' => 44, 'code' => 'HAM', 'team' => 'Scuderia Ferrari', 'nationality' => 'British', 'country_code' => 'gb', 'date_of_birth' => '1985-01-07', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/20.png', 'championships' => 7, 'race_wins' => 105, 'podiums' => 201, 'pole_positions' => 104],
            
            // McLaren
            ['first_name' => 'Lando', 'last_name' => 'Norris', 'number' => 4, 'code' => 'NOR', 'team' => 'McLaren F1 Team', 'nationality' => 'British', 'country_code' => 'gb', 'date_of_birth' => '1999-11-13', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/16.png', 'championships' => 0, 'race_wins' => 4, 'podiums' => 23, 'pole_positions' => 5],
            ['first_name' => 'Oscar', 'last_name' => 'Piastri', 'number' => 81, 'code' => 'PIA', 'team' => 'McLaren F1 Team', 'nationality' => 'Australian', 'country_code' => 'au', 'date_of_birth' => '2001-04-06', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/81.png', 'championships' => 0, 'race_wins' => 2, 'podiums' => 11, 'pole_positions' => 0],
            
            // Aston Martin
            ['first_name' => 'Fernando', 'last_name' => 'Alonso', 'number' => 14, 'code' => 'ALO', 'team' => 'Aston Martin F1 Team', 'nationality' => 'Spanish', 'country_code' => 'es', 'date_of_birth' => '1981-07-29', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/5.png', 'championships' => 2, 'race_wins' => 32, 'podiums' => 106, 'pole_positions' => 22],
            ['first_name' => 'Lance', 'last_name' => 'Stroll', 'number' => 18, 'code' => 'STR', 'team' => 'Aston Martin F1 Team', 'nationality' => 'Canadian', 'country_code' => 'ca', 'date_of_birth' => '1998-10-29', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/10.png', 'championships' => 0, 'race_wins' => 0, 'podiums' => 3, 'pole_positions' => 1],
            
            // Alpine
            ['first_name' => 'Pierre', 'last_name' => 'Gasly', 'number' => 10, 'code' => 'GAS', 'team' => 'Alpine F1 Team', 'nationality' => 'French', 'country_code' => 'fr', 'date_of_birth' => '1996-02-07', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/26.png', 'championships' => 0, 'race_wins' => 1, 'podiums' => 4, 'pole_positions' => 0],
            ['first_name' => 'Jack', 'last_name' => 'Doohan', 'number' => 7, 'code' => 'DOO', 'team' => 'Alpine F1 Team', 'nationality' => 'Australian', 'country_code' => 'au', 'date_of_birth' => '2003-01-20', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/7.png', 'championships' => 0, 'race_wins' => 0, 'podiums' => 0, 'pole_positions' => 0],
            
            // Williams
            ['first_name' => 'Alex', 'last_name' => 'Albon', 'number' => 23, 'code' => 'ALB', 'team' => 'Williams Racing', 'nationality' => 'Thai', 'country_code' => 'th', 'date_of_birth' => '1996-03-23', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/23.png', 'championships' => 0, 'race_wins' => 0, 'podiums' => 2, 'pole_positions' => 0],
            ['first_name' => 'Carlos', 'last_name' => 'Sainz', 'number' => 55, 'code' => 'SAI', 'team' => 'Williams Racing', 'nationality' => 'Spanish', 'country_code' => 'es', 'date_of_birth' => '1994-09-01', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/24.png', 'championships' => 0, 'race_wins' => 4, 'podiums' => 26, 'pole_positions' => 6],
            
            // RB
            ['first_name' => 'Yuki', 'last_name' => 'Tsunoda', 'number' => 22, 'code' => 'TSU', 'team' => 'Visa Cash App RB F1 Team', 'nationality' => 'Japanese', 'country_code' => 'jp', 'date_of_birth' => '2000-05-11', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/35.png', 'championships' => 0, 'race_wins' => 0, 'podiums' => 0, 'pole_positions' => 0],
            ['first_name' => 'Isack', 'last_name' => 'Hadjar', 'number' => 6, 'code' => 'HAD', 'team' => 'Visa Cash App RB F1 Team', 'nationality' => 'French', 'country_code' => 'fr', 'date_of_birth' => '2004-09-28', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/6.png', 'championships' => 0, 'race_wins' => 0, 'podiums' => 0, 'pole_positions' => 0],
            
            // Sauber
            ['first_name' => 'Nico', 'last_name' => 'Hulkenberg', 'number' => 27, 'code' => 'HUL', 'team' => 'Stake F1 Team Kick Sauber', 'nationality' => 'German', 'country_code' => 'de', 'date_of_birth' => '1987-08-19', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/15.png', 'championships' => 0, 'race_wins' => 0, 'podiums' => 0, 'pole_positions' => 1],
            ['first_name' => 'Gabriel', 'last_name' => 'Bortoleto', 'number' => 5, 'code' => 'BOR', 'team' => 'Stake F1 Team Kick Sauber', 'nationality' => 'Brazilian', 'country_code' => 'br', 'date_of_birth' => '2004-10-14', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/5.png', 'championships' => 0, 'race_wins' => 0, 'podiums' => 0, 'pole_positions' => 0],
            
            // Haas
            ['first_name' => 'Esteban', 'last_name' => 'Ocon', 'number' => 31, 'code' => 'OCO', 'team' => 'MoneyGram Haas F1 Team', 'nationality' => 'French', 'country_code' => 'fr', 'date_of_birth' => '1996-09-17', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/14.png', 'championships' => 0, 'race_wins' => 1, 'podiums' => 3, 'pole_positions' => 0],
            ['first_name' => 'Oliver', 'last_name' => 'Bearman', 'number' => 87, 'code' => 'BEA', 'team' => 'MoneyGram Haas F1 Team', 'nationality' => 'British', 'country_code' => 'gb', 'date_of_birth' => '2005-05-08', 'image_url' => 'https://media.api-sports.io/formula-1/drivers/87.png', 'championships' => 0, 'race_wins' => 0, 'podiums' => 0, 'pole_positions' => 0],
        ];

        foreach ($drivers as $driver) {
            $team = Team::where('name', $driver['team'])->first();
            
            Driver::updateOrCreate(
                ['number' => $driver['number']],
                [
                    'first_name' => $driver['first_name'],
                    'last_name' => $driver['last_name'],
                    'code' => $driver['code'],
                    'slug' => Str::slug($driver['first_name'] . ' ' . $driver['last_name']),
                    'nationality' => $driver['nationality'],
                    'country_code' => $driver['country_code'],
                    'date_of_birth' => $driver['date_of_birth'],
                    'image_url' => $driver['image_url'],
                    'championships' => $driver['championships'],
                    'race_wins' => $driver['race_wins'],
                    'podiums' => $driver['podiums'],
                    'pole_positions' => $driver['pole_positions'],
                    'team_id' => $team?->id,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Drivers seeded successfully.');
    }

    protected function seedCircuits(): void
    {
        $circuits = [
            ['name' => 'Bahrain International Circuit', 'country' => 'Bahrain', 'city' => 'Sakhir', 'length' => 5.412],
            ['name' => 'Jeddah Corniche Circuit', 'country' => 'Saudi Arabia', 'city' => 'Jeddah', 'length' => 6.174],
            ['name' => 'Albert Park Circuit', 'country' => 'Australia', 'city' => 'Melbourne', 'length' => 5.278],
            ['name' => 'Suzuka International Racing Course', 'country' => 'Japan', 'city' => 'Suzuka', 'length' => 5.807],
            ['name' => 'Shanghai International Circuit', 'country' => 'China', 'city' => 'Shanghai', 'length' => 5.451],
            ['name' => 'Miami International Autodrome', 'country' => 'United States', 'city' => 'Miami', 'length' => 5.412],
            ['name' => 'Autodromo Enzo e Dino Ferrari', 'country' => 'Italy', 'city' => 'Imola', 'length' => 4.909],
            ['name' => 'Circuit de Monaco', 'country' => 'Monaco', 'city' => 'Monte Carlo', 'length' => 3.337],
            ['name' => 'Circuit Gilles Villeneuve', 'country' => 'Canada', 'city' => 'Montreal', 'length' => 4.361],
            ['name' => 'Circuit de Barcelona-Catalunya', 'country' => 'Spain', 'city' => 'Barcelona', 'length' => 4.675],
            ['name' => 'Red Bull Ring', 'country' => 'Austria', 'city' => 'Spielberg', 'length' => 4.318],
            ['name' => 'Silverstone Circuit', 'country' => 'United Kingdom', 'city' => 'Silverstone', 'length' => 5.891],
            ['name' => 'Hungaroring', 'country' => 'Hungary', 'city' => 'Budapest', 'length' => 4.381],
            ['name' => 'Circuit de Spa-Francorchamps', 'country' => 'Belgium', 'city' => 'Spa', 'length' => 7.004],
            ['name' => 'Circuit Zandvoort', 'country' => 'Netherlands', 'city' => 'Zandvoort', 'length' => 4.259],
            ['name' => 'Autodromo Nazionale Monza', 'country' => 'Italy', 'city' => 'Monza', 'length' => 5.793],
            ['name' => 'Baku City Circuit', 'country' => 'Azerbaijan', 'city' => 'Baku', 'length' => 6.003],
            ['name' => 'Marina Bay Street Circuit', 'country' => 'Singapore', 'city' => 'Singapore', 'length' => 4.940],
            ['name' => 'Circuit of the Americas', 'country' => 'United States', 'city' => 'Austin', 'length' => 5.513],
            ['name' => 'Autódromo Hermanos Rodríguez', 'country' => 'Mexico', 'city' => 'Mexico City', 'length' => 4.304],
            ['name' => 'Autódromo José Carlos Pace', 'country' => 'Brazil', 'city' => 'São Paulo', 'length' => 4.309],
            ['name' => 'Las Vegas Street Circuit', 'country' => 'United States', 'city' => 'Las Vegas', 'length' => 6.201],
            ['name' => 'Losail International Circuit', 'country' => 'Qatar', 'city' => 'Lusail', 'length' => 5.419],
            ['name' => 'Yas Marina Circuit', 'country' => 'United Arab Emirates', 'city' => 'Abu Dhabi', 'length' => 5.281],
        ];

        foreach ($circuits as $circuit) {
            Circuit::updateOrCreate(
                ['name' => $circuit['name']],
                [
                    'slug' => Str::slug($circuit['name']),
                    'country' => $circuit['country'],
                    'city' => $circuit['city'],
                    'length' => $circuit['length'],
                ]
            );
        }

        $this->command->info('Circuits seeded successfully.');
    }

    protected function seedRaces(): void
    {
        $races = [
            ['name' => 'Australian Grand Prix', 'circuit' => 'Albert Park Circuit', 'round' => 1, 'date' => '2026-03-16'],
            ['name' => 'Chinese Grand Prix', 'circuit' => 'Shanghai International Circuit', 'round' => 2, 'date' => '2026-03-23'],
            ['name' => 'Japanese Grand Prix', 'circuit' => 'Suzuka International Racing Course', 'round' => 3, 'date' => '2026-04-06'],
            ['name' => 'Bahrain Grand Prix', 'circuit' => 'Bahrain International Circuit', 'round' => 4, 'date' => '2026-04-13'],
            ['name' => 'Saudi Arabian Grand Prix', 'circuit' => 'Jeddah Corniche Circuit', 'round' => 5, 'date' => '2026-04-20'],
            ['name' => 'Miami Grand Prix', 'circuit' => 'Miami International Autodrome', 'round' => 6, 'date' => '2026-05-04'],
            ['name' => 'Emilia Romagna Grand Prix', 'circuit' => 'Autodromo Enzo e Dino Ferrari', 'round' => 7, 'date' => '2026-05-18'],
            ['name' => 'Monaco Grand Prix', 'circuit' => 'Circuit de Monaco', 'round' => 8, 'date' => '2026-05-25'],
            ['name' => 'Spanish Grand Prix', 'circuit' => 'Circuit de Barcelona-Catalunya', 'round' => 9, 'date' => '2026-06-01'],
            ['name' => 'Canadian Grand Prix', 'circuit' => 'Circuit Gilles Villeneuve', 'round' => 10, 'date' => '2026-06-15'],
            ['name' => 'Austrian Grand Prix', 'circuit' => 'Red Bull Ring', 'round' => 11, 'date' => '2026-06-29'],
            ['name' => 'British Grand Prix', 'circuit' => 'Silverstone Circuit', 'round' => 12, 'date' => '2026-07-06'],
            ['name' => 'Belgian Grand Prix', 'circuit' => 'Circuit de Spa-Francorchamps', 'round' => 13, 'date' => '2026-07-27'],
            ['name' => 'Hungarian Grand Prix', 'circuit' => 'Hungaroring', 'round' => 14, 'date' => '2026-08-03'],
            ['name' => 'Dutch Grand Prix', 'circuit' => 'Circuit Zandvoort', 'round' => 15, 'date' => '2026-08-31'],
            ['name' => 'Italian Grand Prix', 'circuit' => 'Autodromo Nazionale Monza', 'round' => 16, 'date' => '2026-09-07'],
            ['name' => 'Azerbaijan Grand Prix', 'circuit' => 'Baku City Circuit', 'round' => 17, 'date' => '2026-09-21'],
            ['name' => 'Singapore Grand Prix', 'circuit' => 'Marina Bay Street Circuit', 'round' => 18, 'date' => '2026-10-05'],
            ['name' => 'United States Grand Prix', 'circuit' => 'Circuit of the Americas', 'round' => 19, 'date' => '2026-10-19'],
            ['name' => 'Mexico City Grand Prix', 'circuit' => 'Autódromo Hermanos Rodríguez', 'round' => 20, 'date' => '2026-10-26'],
            ['name' => 'São Paulo Grand Prix', 'circuit' => 'Autódromo José Carlos Pace', 'round' => 21, 'date' => '2026-11-09'],
            ['name' => 'Las Vegas Grand Prix', 'circuit' => 'Las Vegas Street Circuit', 'round' => 22, 'date' => '2026-11-22'],
            ['name' => 'Qatar Grand Prix', 'circuit' => 'Losail International Circuit', 'round' => 23, 'date' => '2026-11-30'],
            ['name' => 'Abu Dhabi Grand Prix', 'circuit' => 'Yas Marina Circuit', 'round' => 24, 'date' => '2026-12-08'],
        ];

        foreach ($races as $race) {
            $circuit = Circuit::where('name', $race['circuit'])->first();
            $raceDate = \Carbon\Carbon::parse($race['date'] . ' 15:00:00');
            
            Race::updateOrCreate(
                ['name' => $race['name'], 'season' => 2026],
                [
                    'slug' => Str::slug($race['name'] . ' 2026'),
                    'circuit_id' => $circuit?->id,
                    'round' => $race['round'],
                    'race_date' => $raceDate,
                    'status' => $raceDate->isPast() ? 'completed' : 'scheduled',
                ]
            );
        }

        $this->command->info('Races seeded successfully.');
    }

    protected function seedStandings(): void
    {
        // Initialize 2026 season standings with zero points
        $drivers = Driver::with('team')->where('is_active', true)->get();
        
        foreach ($drivers as $index => $driver) {
            DriverStanding::updateOrCreate(
                ['driver_id' => $driver->id, 'season' => 2026],
                [
                    'position' => $index + 1,
                    'points' => 0,
                    'wins' => 0,
                ]
            );
        }

        $this->command->info('Standings seeded successfully for 2026 season.');
    }
}
