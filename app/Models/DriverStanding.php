<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverStanding extends Model
{
    use HasFactory;

    protected $fillable = [
        'season',
        'round',
        'driver_id',
        'team_id',
        'position',
        'points',
        'wins',
    ];

    protected $casts = [
        'season' => 'integer',
        'round' => 'integer',
        'position' => 'integer',
        'points' => 'decimal:1',
        'wins' => 'integer',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    // Scopes
    public function scopeCurrentSeason($query)
    {
        return $query->where('season', now()->year);
    }

    public function scopeLatest($query)
    {
        return $query->orderByDesc('season')
            ->orderByDesc('round');
    }

    public function scopeForSeason($query, int $season)
    {
        return $query->where('season', $season);
    }

    // Static methods
    public static function getCurrentStandings(): \Illuminate\Database\Eloquent\Collection
    {
        $latest = static::currentSeason()
            ->latest()
            ->first();
        
        if (!$latest) {
            return new \Illuminate\Database\Eloquent\Collection();
        }

        return static::where('season', $latest->season)
            ->when($latest->round, fn($q) => $q->where('round', $latest->round))
            ->with(['driver', 'team'])
            ->orderBy('position')
            ->get();
    }
}
