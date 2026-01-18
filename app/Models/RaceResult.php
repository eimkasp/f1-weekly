<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaceResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'race_id',
        'driver_id',
        'team_id',
        'position',
        'grid_position',
        'points',
        'laps_completed',
        'time',
        'fastest_lap',
        'fastest_lap_number',
        'has_fastest_lap',
        'status',
        'status_detail',
    ];

    protected $casts = [
        'position' => 'integer',
        'grid_position' => 'integer',
        'points' => 'decimal:1',
        'laps_completed' => 'integer',
        'fastest_lap_number' => 'integer',
        'has_fastest_lap' => 'boolean',
    ];

    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    // Scopes
    public function scopeFinished($query)
    {
        return $query->where('status', 'finished');
    }

    public function scopeDnf($query)
    {
        return $query->where('status', 'DNF');
    }

    public function scopePointsFinish($query)
    {
        return $query->where('position', '<=', 10);
    }

    public function scopePodium($query)
    {
        return $query->whereIn('position', [1, 2, 3]);
    }

    // Accessors
    public function getPositionChangeAttribute(): int
    {
        if (!$this->grid_position || !$this->position) {
            return 0;
        }
        return $this->grid_position - $this->position;
    }

    public function getPositionChangeDisplayAttribute(): string
    {
        $change = $this->position_change;
        
        if ($change > 0) {
            return "↑{$change}";
        } elseif ($change < 0) {
            return "↓" . abs($change);
        }
        return "―";
    }

    public function getIsWinnerAttribute(): bool
    {
        return $this->position === 1;
    }

    public function getIsPodiumAttribute(): bool
    {
        return $this->position !== null && $this->position <= 3;
    }

    public function getIsPointsFinishAttribute(): bool
    {
        return $this->position !== null && $this->position <= 10;
    }

    public function getPositionDisplayAttribute(): string
    {
        if ($this->position === null) {
            return $this->status ?? 'N/A';
        }
        
        $suffix = match($this->position) {
            1 => 'st',
            2 => 'nd',
            3 => 'rd',
            default => 'th',
        };
        
        return $this->position . $suffix;
    }
}
