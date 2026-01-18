<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsSource extends Model
{
    use HasFactory;

    public const TYPE_RSS = 'rss';
    public const TYPE_API = 'api';
    public const TYPE_SCRAPE = 'scrape';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'url',
        'logo',
        'language',
        'requires_translation',
        'translate_to',
        'priority',
        'fetch_interval',
        'is_active',
        'config',
        'last_fetched_at',
    ];

    protected $casts = [
        'requires_translation' => 'boolean',
        'priority' => 'integer',
        'fetch_interval' => 'integer',
        'is_active' => 'boolean',
        'config' => 'array',
        'last_fetched_at' => 'datetime',
    ];

    public function rawContents(): HasMany
    {
        return $this->hasMany(RawContent::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNeedsFetch($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('last_fetched_at')
                    ->orWhereRaw('last_fetched_at < DATE_SUB(NOW(), INTERVAL fetch_interval SECOND)');
            });
    }

    public function scopeByPriority($query)
    {
        return $query->orderByDesc('priority');
    }

    public function scopeRequiresTranslation($query)
    {
        return $query->where('requires_translation', true);
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function markAsFetched(): void
    {
        $this->update(['last_fetched_at' => now()]);
    }

    public function needsFetch(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->last_fetched_at) {
            return true;
        }

        return $this->last_fetched_at->addSeconds($this->fetch_interval)->isPast();
    }
}
