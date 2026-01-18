<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'name',
        'short_name',
        'slug',
        'logo',
        'base',
        'team_principal',
        'chassis',
        'power_unit',
        'founded',
        'world_championships',
        'color',
        'description',
        'is_active',
    ];

    protected $casts = [
        'founded' => 'integer',
        'world_championships' => 'integer',
        'is_active' => 'boolean',
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

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }

    public function raceResults(): HasMany
    {
        return $this->hasMany(RaceResult::class);
    }

    public function constructorStandings(): HasMany
    {
        return $this->hasMany(ConstructorStanding::class);
    }

    public function news(): BelongsToMany
    {
        return $this->belongsToMany(News::class, 'team_news');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getColorStyleAttribute(): string
    {
        return $this->color ? "background-color: {$this->color};" : '';
    }

    public function getCurrentStandingAttribute()
    {
        return $this->constructorStandings()
            ->orderByDesc('season')
            ->orderByDesc('round')
            ->first();
    }
}
