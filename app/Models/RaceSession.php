<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'race_id',
        'type',
        'date',
        'time',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i:s',
    ];

    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    // Accessors
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'FP1' => 'Free Practice 1',
            'FP2' => 'Free Practice 2',
            'FP3' => 'Free Practice 3',
            'qualifying' => 'Qualifying',
            'sprint_qualifying' => 'Sprint Qualifying',
            'sprint' => 'Sprint Race',
            'race' => 'Race',
            default => $this->type,
        };
    }

    public function getIsLiveAttribute(): bool
    {
        return $this->status === 'live';
    }
}
