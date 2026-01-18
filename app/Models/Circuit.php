<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Circuit extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'name',
        'slug',
        'location',
        'country',
        'country_code',
        'length',
        'corners',
        'drs_zones',
        'lap_record',
        'lap_record_holder',
        'lap_record_year',
        'image',
        'latitude',
        'longitude',
        'first_grand_prix',
        'description',
    ];

    protected $casts = [
        'length' => 'decimal:3',
        'corners' => 'integer',
        'drs_zones' => 'integer',
        'lap_record_year' => 'integer',
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
        'first_grand_prix' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function races(): HasMany
    {
        return $this->hasMany(Race::class);
    }

    // Accessors
    public function getCoordinatesAttribute(): ?array
    {
        if ($this->latitude && $this->longitude) {
            return [
                'lat' => (float) $this->latitude,
                'lng' => (float) $this->longitude,
            ];
        }
        return null;
    }

    public function getLengthFormattedAttribute(): string
    {
        return $this->length ? number_format($this->length, 3) . ' km' : 'N/A';
    }

    public function getLapRecordDisplayAttribute(): string
    {
        if (!$this->lap_record) {
            return 'N/A';
        }
        
        $display = $this->lap_record;
        if ($this->lap_record_holder) {
            $display .= " ({$this->lap_record_holder}";
            if ($this->lap_record_year) {
                $display .= ", {$this->lap_record_year}";
            }
            $display .= ")";
        }
        
        return $display;
    }

    /**
     * Get the ISO 3166-1 alpha-2 country code for flagpedia
     */
    public function getCountryCodeAttribute(): string
    {
        $countryCodeMap = [
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
            'Turkey' => 'tr',
            'South Korea' => 'kr',
            'Malaysia' => 'my',
            'India' => 'in',
            'South Africa' => 'za',
            'Vietnam' => 'vn',
        ];

        return $countryCodeMap[$this->country] ?? $this->country_code ?? 'un';
    }

    /**
     * Get the flag URL from flagcdn.com
     */
    public function getFlagUrlAttribute(): string
    {
        return "https://flagcdn.com/w160/{$this->country_code}.png";
    }

    /**
     * Get the SVG flag URL from flagcdn.com
     */
    public function getFlagSvgUrlAttribute(): string
    {
        return "https://flagcdn.com/{$this->country_code}.svg";
    }
}
