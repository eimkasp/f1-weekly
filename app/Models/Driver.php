<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'team_id',
        'first_name',
        'last_name',
        'slug',
        'code',
        'number',
        'nationality',
        'country_code',
        'date_of_birth',
        'place_of_birth',
        'image_url',
        'biography',
        'championships',
        'race_wins',
        'podiums',
        'pole_positions',
        'fastest_laps',
        'career_points',
        'is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'number' => 'integer',
        'championships' => 'integer',
        'race_wins' => 'integer',
        'podiums' => 'integer',
        'pole_positions' => 'integer',
        'fastest_laps' => 'integer',
        'career_points' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $appends = ['name'];

    /**
     * Get the driver's full name
     */
    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    protected static function boot(): void
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function raceResults(): HasMany
    {
        return $this->hasMany(RaceResult::class);
    }

    public function standings(): HasMany
    {
        return $this->hasMany(DriverStanding::class);
    }

    public function news(): BelongsToMany
    {
        return $this->belongsToMany(News::class, 'driver_news');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithTeam($query)
    {
        return $query->whereNotNull('team_id');
    }

    // Accessors
    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth?->age;
    }

    public function getCurrentStandingAttribute()
    {
        return $this->standings()
            ->orderByDesc('season')
            ->orderByDesc('round')
            ->first();
    }

    public function getTeamColorAttribute(): ?string
    {
        return $this->team?->color;
    }

    public function getDisplayNumberAttribute(): string
    {
        return $this->number ? str_pad($this->number, 2, '0', STR_PAD_LEFT) : '--';
    }

    // Methods
    public function getRecentResults(int $count = 5)
    {
        return $this->raceResults()
            ->with('race')
            ->orderByDesc('created_at')
            ->limit($count)
            ->get();
    }

    public function getSeasonResults(int $season)
    {
        return $this->raceResults()
            ->whereHas('race', fn($q) => $q->where('season', $season))
            ->with('race')
            ->orderBy('created_at')
            ->get();
    }
}
