<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolymarketMarket extends Model
{
    use HasFactory;

    protected $fillable = [
        'condition_id',
        'question_id',
        'title',
        'description',
        'category',
        'market_type',
        'race_id',
        'driver_id',
        'team_id',
        'outcome_yes',
        'outcome_no',
        'price_yes',
        'price_no',
        'volume',
        'liquidity',
        'volume_24h',
        'is_active',
        'end_date',
        'resolution_date',
        'resolution',
        'tokens',
        'orderbook_snapshot',
        'last_synced_at',
    ];

    protected $casts = [
        'price_yes' => 'decimal:4',
        'price_no' => 'decimal:4',
        'volume' => 'decimal:2',
        'liquidity' => 'decimal:2',
        'volume_24h' => 'integer',
        'is_active' => 'boolean',
        'end_date' => 'datetime',
        'resolution_date' => 'datetime',
        'tokens' => 'array',
        'orderbook_snapshot' => 'array',
        'last_synced_at' => 'datetime',
    ];

    // Relationships
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
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForRace($query, $raceId)
    {
        return $query->where('race_id', $raceId);
    }

    public function scopeForDriver($query, $driverId)
    {
        return $query->where('driver_id', $driverId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('market_type', $type);
    }

    public function scopeF1Markets($query)
    {
        return $query->where('category', 'f1');
    }

    // Accessors
    public function getImpliedProbabilityYesAttribute(): ?float
    {
        return $this->price_yes ? round($this->price_yes * 100, 1) : null;
    }

    public function getImpliedProbabilityNoAttribute(): ?float
    {
        return $this->price_no ? round($this->price_no * 100, 1) : null;
    }

    public function getOddsYesAttribute(): ?string
    {
        if (!$this->price_yes || $this->price_yes <= 0) return null;
        
        $decimal = 1 / $this->price_yes;
        return number_format($decimal, 2);
    }

    public function getOddsNoAttribute(): ?string
    {
        if (!$this->price_no || $this->price_no <= 0) return null;
        
        $decimal = 1 / $this->price_no;
        return number_format($decimal, 2);
    }

    public function getFormattedVolumeAttribute(): string
    {
        if ($this->volume >= 1000000) {
            return '$' . number_format($this->volume / 1000000, 1) . 'M';
        }
        if ($this->volume >= 1000) {
            return '$' . number_format($this->volume / 1000, 1) . 'K';
        }
        return '$' . number_format($this->volume, 0);
    }

    public function getIsResolvedAttribute(): bool
    {
        return $this->resolution !== null;
    }

    public function getStatusAttribute(): string
    {
        if ($this->resolution !== null) {
            return 'resolved';
        }
        if (!$this->is_active) {
            return 'closed';
        }
        if ($this->end_date && $this->end_date->isPast()) {
            return 'ended';
        }
        return 'active';
    }

    // Methods
    public function updatePrices(float $yesPrice, float $noPrice): void
    {
        $this->update([
            'price_yes' => $yesPrice,
            'price_no' => $noPrice,
            'last_synced_at' => now(),
        ]);
    }

    public function markResolved(string $outcome): void
    {
        $this->update([
            'resolution' => $outcome,
            'resolution_date' => now(),
            'is_active' => false,
        ]);
    }
}
