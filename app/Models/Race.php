<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Race extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'circuit_id',
        'season',
        'round',
        'name',
        'slug',
        'official_name',
        'race_date',
        'timezone',
        'status',
        'laps',
        'distance',
        'image',
        'is_sprint_weekend',
    ];

    protected $casts = [
        'race_date' => 'datetime',
        'season' => 'integer',
        'round' => 'integer',
        'laps' => 'integer',
        'is_sprint_weekend' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name . '-' . $model->season);
            }
        });
    }

    public function circuit(): BelongsTo
    {
        return $this->belongsTo(Circuit::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(RaceResult::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(RaceSession::class);
    }

    public function news(): BelongsToMany
    {
        return $this->belongsToMany(News::class, 'race_news');
    }

    // Scopes
    public function scopeCurrentSeason($query)
    {
        return $query->where('season', now()->year);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('race_date', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('race_date');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')
            ->orderByDesc('race_date');
    }

    public function scopeLive($query)
    {
        return $query->where('status', 'live');
    }

    // Accessors
    public function getIsUpcomingAttribute(): bool
    {
        return $this->race_date >= now() && $this->status === 'scheduled';
    }

    public function getIsLiveAttribute(): bool
    {
        return $this->status === 'live';
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    // Helper methods
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isUpcoming(): bool
    {
        return $this->race_date >= now() && $this->status === 'scheduled';
    }

    public function isLive(): bool
    {
        return $this->status === 'live';
    }

    public function getCountdownAttribute(): ?array
    {
        if (!$this->is_upcoming || !$this->race_date) {
            return null;
        }

        $now = now();
        $raceDate = $this->race_date;
        
        if ($raceDate->isPast()) {
            return null;
        }

        $diff = $now->diff($raceDate);
        
        return [
            'days' => $diff->days,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s,
        ];
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->race_date?->format('F j, Y') ?? 'TBD';
    }

    /**
     * Get the flag URL for this race's country
     */
    public function getFlagUrlAttribute(): string
    {
        return $this->circuit?->flag_url ?? 'https://flagcdn.com/w160/un.png';
    }

    public function getWinnerAttribute()
    {
        return $this->results()
            ->where('position', 1)
            ->with(['driver', 'team'])
            ->first();
    }

    public function getPodiumAttribute()
    {
        return $this->results()
            ->whereIn('position', [1, 2, 3])
            ->with(['driver', 'team'])
            ->orderBy('position')
            ->get();
    }
}
