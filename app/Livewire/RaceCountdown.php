<?php

namespace App\Livewire;

use App\Models\Race;
use Livewire\Component;

class RaceCountdown extends Component
{
    public ?Race $race = null;
    public bool $showDetails = true;
    public string $size = 'default'; // compact, default, large

    /**
     * Country to ISO 3166-1 alpha-2 code mapping for flagpedia
     */
    protected array $countryCodeMap = [
        'Australia' => 'au',
        'Austria' => 'at',
        'Azerbaijan' => 'az',
        'Bahrain' => 'bh',
        'Belgium' => 'be',
        'Brazil' => 'br',
        'Canada' => 'ca',
        'China' => 'cn',
        'France' => 'fr',
        'Germany' => 'de',
        'Hungary' => 'hu',
        'Italy' => 'it',
        'Japan' => 'jp',
        'Mexico' => 'mx',
        'Monaco' => 'mc',
        'Netherlands' => 'nl',
        'Portugal' => 'pt',
        'Qatar' => 'qa',
        'Russia' => 'ru',
        'Saudi Arabia' => 'sa',
        'Singapore' => 'sg',
        'Spain' => 'es',
        'UAE' => 'ae',
        'United Arab Emirates' => 'ae',
        'UK' => 'gb',
        'United Kingdom' => 'gb',
        'Great Britain' => 'gb',
        'USA' => 'us',
        'United States' => 'us',
        'Las Vegas' => 'us',
        'Miami' => 'us',
        'Turkey' => 'tr',
        'South Korea' => 'kr',
        'Malaysia' => 'my',
        'India' => 'in',
        'South Africa' => 'za',
        'Vietnam' => 'vn',
    ];

    public function mount(?Race $race = null, bool $showDetails = true, string $size = 'default'): void
    {
        $this->race = $race ?? $this->getNextRace();
        $this->showDetails = $showDetails;
        $this->size = $size;
    }

    protected function getNextRace(): ?Race
    {
        return Race::with('circuit')
            ->where('race_date', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('race_date')
            ->first();
    }

    public function getCountryCodeProperty(): string
    {
        if (!$this->race?->circuit?->country) {
            return 'un'; // United Nations flag as fallback
        }

        $country = $this->race->circuit->country;
        
        return $this->countryCodeMap[$country] ?? 'un';
    }

    public function getFlagUrlProperty(): string
    {
        $code = $this->countryCode;
        
        // Flagpedia provides flags in multiple sizes
        // w80, w160, w320, w640, w1280, w2560
        $width = match ($this->size) {
            'compact' => 'w80',
            'large' => 'w320',
            default => 'w160',
        };
        
        return "https://flagcdn.com/{$width}/{$code}.png";
    }

    public function getFlagSvgUrlProperty(): string
    {
        return "https://flagcdn.com/{$this->countryCode}.svg";
    }

    public function getRaceDateIsoProperty(): ?string
    {
        return $this->race?->race_date?->toIso8601String();
    }

    public function getRaceUrlProperty(): string
    {
        if (!$this->race) {
            return '#';
        }
        
        try {
            return route('race.show', $this->race);
        } catch (\Exception $e) {
            // Fallback to admin races page if route doesn't exist
            return '/admin/races';
        }
    }

    public function render()
    {
        return view('livewire.race-countdown');
    }
}
