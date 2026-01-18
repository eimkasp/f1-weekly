<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructorStanding extends Model
{
    use HasFactory;

    protected $fillable = [
        'season',
        'round',
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
            return collect();
        }

        return static::where('season', $latest->season)
            ->where('round', $latest->round)
            ->with('team')
            ->orderBy('position')
            ->get();
    }
}
